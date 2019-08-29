<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 14.08.2019
 * Time: 23:14
 */

namespace App\Controller;


use App\Entity\League;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class LeagueApiController
 * @package App\Controller
 *
 * @Route("/league")
 */
class LeagueApiController extends AbstractApiController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $leagues = $em->getRepository(League::class)->findAll();
        $json = $serializer->serialize($leagues, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(SerializerInterface $serializer, League $league)
    {
        $json = $serializer->serialize($league, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/teams/{id}", methods={"GET"})
     */
    public function teamsShow(SerializerInterface $serializer, League $league)
    {
        $json = $serializer->serialize($league->getTeams(), "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/teams", methods={"POST"})
     */
    public function teamsCreate(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $leagues = $em->getRepository(League::class)->findAll();
        foreach ($leagues as $league) {
            $httpClient = HttpClient::create();
            $response = $httpClient->request(
                'GET',
                'https://api-football-v1.p.rapidapi.com/v2/teams/league/'.$league->getId(),
                [
                    'headers' => [
                        'X-RapidAPI-Key' => '8ee277dd62mshed7303c40945b69p1b0bf1jsneee3b18794dd',
                    ],
                ]
            );

            $data = json_decode($response->getContent(), true);
            $api = $data['api'] ?? null;
            $api ? $teams = $api['teams'] : $teams = null;

            if ($teams){
                foreach ($teams as $team){
                    $team['league_id'] = $league->getId();
                    $entity = $serializer->deserialize(json_encode($team), Team::class,'json');
                    if ($entity){
                        $em->persist($entity);
                    }
                }
                $em->flush();
            }
        }


        $entities = $em->getRepository(Team::class)->findAll();
        $json = $serializer->serialize($entities, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }
}
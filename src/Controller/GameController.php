<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 19.08.2019
 * Time: 20:48
 */

namespace App\Controller;


use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class GameController
 * @package App\Controller
 *
 * @Route("/game")
 */
class GameController extends AbstractApiController
{
    /**
     * @Route("/add")
     */
    public function addGames(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v2/fixtures/date/'.date('Y-m-d'),
            [
                'headers' => [
                    'X-RapidAPI-Key' => '8ee277dd62mshed7303c40945b69p1b0bf1jsneee3b18794dd',
                ],
            ]
        );

        $data = json_decode($response->getContent(), true);
        $api = $data['api'] ?? null;
        $api ? $games = $api['fixtures'] : $games = null;

        if ($games){
            foreach ($games as $game){
                $entity = $serializer->deserialize(json_encode($game), Game::class,'json');
                if ($entity){
                    $em->persist($entity);
                }
            }
            $em->flush();
        }

        $entities = $em->getRepository(Game::class)->findAll();
        $json = $serializer->serialize($entities, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }
}
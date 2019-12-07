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
use App\Service\RapidApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
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
     * @var RapidApiService $rapidApiService
     */
    private $rapidApiService;

    public function __construct(RapidApiService $rapidApiService)
    {
        $this->rapidApiService = $rapidApiService;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $leagues = $em->getRepository(League::class)->findAll();
        $json = $serializer->serialize($leagues, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function show(Request $request, SerializerInterface $serializer, League $league)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $json = $serializer->serialize($league, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/add/{id}", methods={"POST"})
     */
    public function new(int $id, SerializerInterface $serializer, Request $request, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $league = $this->rapidApiService->getLeagueById($this->getParameter('rapid_api_key'), $id);
        if (!$league) {
            throw new HttpException(404, "League not found");
        }

        $entity = $serializer->deserialize(json_encode($league), League::class,'json');
        $em->persist($entity);
        $em->flush();

        $json = $serializer->serialize($entity, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/{id}/teams", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function teamsShow(Request $request, SerializerInterface $serializer, League $league)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $json = $serializer->serialize($league->getTeams(), "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/teams/add", methods={"POST"})
     */
    public function teamsCreate(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $leagues = $em->getRepository(League::class)->findAll();
        foreach ($leagues as $league) {
            $teams = $response = $this->rapidApiService->leagueTeams($this->getParameter('rapid_api_key'), $league);
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
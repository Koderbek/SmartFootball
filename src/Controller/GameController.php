<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 19.08.2019
 * Time: 20:48
 */

namespace App\Controller;


use App\Entity\Game;
use App\Entity\UserInterests;
use App\Service\MessageService;
use App\Service\RapidApiService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var MessageService $messageService
     */
    protected $messageService;

    /**
     * @var RapidApiService $rapidApiService
     */
    private $rapidApiService;

    public function __construct(MessageService $messageService, RapidApiService $rapidApiService)
    {
        $this->messageService = $messageService;
        $this->rapidApiService = $rapidApiService;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $games = $em->getRepository(Game::class)->findAll();
        $json = $serializer->serialize($games, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function addGames(SerializerInterface $serializer, Request $request, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $matches = $em->getRepository(Game::class)->findAll();
        if ($matches) {
            foreach ($matches as $match) {
                $em->remove($match);
            }
            $em->flush();
        }

        $date = $request->query->get('date');
        $games = $this->rapidApiService->todayGames($this->getParameter('rapid_api_key'), $date);
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

    /**
     * @Route("/fetch", methods={"GET"})
     */
    public function fetchGames(SerializerInterface $serializer, Request $request, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $matches = $em->getRepository(Game::class)->findAll();
        if (!$matches) {
            $json = $serializer->serialize([], "json");
            return $this->createResponse($json);
        }

        $date = $request->query->get('date');
        $games = $this->rapidApiService->todayGames($this->getParameter('rapid_api_key'), $date);
        if (!$games) {
            $json = $serializer->serialize([], "json");
            return $this->createResponse($json);
        }

        $alerts = [];
        foreach ($matches as $match) {
            foreach ($games as $game) {
                if ($match->getId() != $game['fixture_id'] || $game['statusShort'] != 'FT') {
                    continue;
                }

                $homeTeam = $game['homeTeam'];
                $awayTeam = $game['awayTeam'];
                if (!$homeTeam || !$awayTeam) {
                    break;
                }

                /** @var UserInterests[] $notifyUsers */
                $notifyUsers = $em->getRepository(UserInterests::class)
                    ->findByTeam([$homeTeam['team_id'], $awayTeam['team_id']]);
                if (!$notifyUsers) {
                    $em->remove($match);
                    break;
                }

                $userIds = [];
                foreach ($notifyUsers as $notifyUser) {
                    $userIds[] = $notifyUser->getUserId();
                }

                $message = $this->messageService->getMessage($game, $match->getLeague()->getLogo());

                $userIds = array_unique($userIds);
                sort($userIds);

                $alerts[] = [
                    'message' => $message,
                    'users' => $userIds,
                    'logo' => $match->getLeague()->getLogo() ?? null,
                ];

                $em->remove($match);
                break;
            }
        }

        $em->flush();
        $json = $serializer->serialize($alerts, "json");

        return $this->createResponse($json);
    }
}
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
     * @var MessageService $messageService
     */
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function addGames(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $matches = $em->getRepository(Game::class)->findAll();
        if ($matches) {
            foreach ($matches as $match) {
                $em->remove($match);
            }
        }

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v2/fixtures/date/'.date('Y-m-d'),
            [
                'headers' => [
                    'X-RapidAPI-Key' => $this->getParameter('rapid_api_key'),
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

    /**
     * @Route("/fetch", methods={"GET"})
     */
    public function fetchGames(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $alerts = [];
        $matches = $em->getRepository(Game::class)->findAll();
        if ($matches) {
            $httpClient = HttpClient::create();
            $response = $httpClient->request(
                'GET',
                'https://api-football-v1.p.rapidapi.com/v2/fixtures/date/2019-08-28'.date('Y-m-d'),
                [
                    'headers' => [
                        'X-RapidAPI-Key' => $this->getParameter('rapid_api_key'),
                    ],
                ]
            );

            $data = json_decode($response->getContent(), true);
            $api = $data['api'] ?? null;
            $api ? $games = $api['fixtures'] : $games = null;

            if ($games){
                foreach ($matches as $match) {
                    foreach ($games as $game){
                        if ($match->getId() == $game['fixture_id'] && $game['statusShort'] == 'FT') {
                            $homeTeam = $game['homeTeam'];
                            $awayTeam = $game['awayTeam'];
                            if ($homeTeam && $awayTeam) {
                                /** @var UserInterests[] $notifyUsers */
                                $notifyUsers = $em->getRepository(UserInterests::class)
                                    ->findByTeam([$homeTeam['team_id'], $awayTeam['team_id']]);

                                $userIds = [];

                                foreach ($notifyUsers as $notifyUser) {
                                    $userIds[] = $notifyUser->getUserId();
                                }

                                $message = $this->messageService->getMessage($game);

                                $userIds = array_unique($userIds);
                                sort($userIds);

                                $alerts[] = [
                                    'message' => $message,
                                    'users' => $userIds
                                ];
                            }

                            $em->remove($match);
                            break;
                        }
                    }
                }

                $em->flush();
            }
        }

        $json = $serializer->serialize($alerts, "json");
        return $this->createResponse($json);
    }
}
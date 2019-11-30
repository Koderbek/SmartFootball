<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 02.09.2019
 * Time: 21:46
 */

namespace App\Service;


use App\Entity\League;
use Symfony\Component\HttpClient\HttpClient;

class RapidApiService
{
    public function leagueTeams($rapidApiKey, League $league)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v2/teams/league/'.$league->getId(),
            [
                'headers' => [
                    'X-RapidAPI-Key' => $rapidApiKey,
                ],
            ]
        );
        $data = json_decode($response->getContent(), true);
        $api = $data['api'] ?? null;
        $api ? $teams = $api['teams'] : $teams = null;

        return $teams;
    }

    public function todayGames($rapidApiKey, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v2/fixtures/date/'.$date,
            [
                'headers' => [
                    'X-RapidAPI-Key' => $rapidApiKey,
                ],
            ]
        );

        $data = json_decode($response->getContent(), true);
        $api = $data['api'] ?? null;
        $api ? $games = $api['fixtures'] : $games = null;

        return $games;
    }

    public function getLeagueById($rapidApiKey, int $id)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v2/leagues/league/'.$id,
            [
                'headers' => [
                    'X-RapidAPI-Key' => $rapidApiKey,
                ],
            ]
        );

        $data = json_decode($response->getContent(), true);
        $api = $data['api'] ?? null;
        $leagues = $api ? $api['leagues'] : null;

        return $leagues[0] ?? null;
    }
}
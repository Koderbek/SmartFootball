<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 29.08.2019
 * Time: 23:36
 */

namespace App\Service;


class MessageService
{
    public function getMessage($game)
    {
        $message = '';
        $message .= 'Матч '.$game['homeTeam']['team_name'].' - '.$game['awayTeam']['team_name'].PHP_EOL;
        $message .= 'Счет матча: '.$game['goalsHomeTeam'].' - '.$game['goalsAwayTeam'].'.';
        return $message;
    }
}
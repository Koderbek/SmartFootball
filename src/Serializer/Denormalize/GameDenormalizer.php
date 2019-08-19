<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 19.08.2019
 * Time: 22:18
 */

namespace App\Serializer\Denormalize;


use App\Entity\League;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GameDenormalizer implements DenormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (key_exists('league_id', $data)){
            $entity = null;
            $league = $this->em->getRepository(League::class)->find($data['league_id']);

            if ($league){
                if (key_exists('fixture_id', $data)) {
                    $entity = $this->em->getRepository(Game::class)->find($data['fixture_id']);
                    if (!$entity) {
                        $entity = new Game();
                        $entity->setId($data['fixture_id']);
                    }
                } else {
                    throw new HttpException(400, "Bad Request");
                }

                $entity->setLeague($league);
                $entity->setDate($data['event_timestamp'] ?? null);
                $entity->setRound($data['round'] ?? null);
                $entity->setStatus($data['statusShort'] ?? null);

                if ($data['homeTeam']){
                    $homeTeam = $data['homeTeam'];
                    $entity->setHomeTeamId($homeTeam['team_id'] ?? null);
                    $entity->setHomeTeamName($homeTeam['team_name'] ?? null);
                }

                if ($data['awayTeam']){
                    $awayTeam = $data['awayTeam'];
                    $entity->setAwayTeamId($awayTeam['team_id'] ?? null);
                    $entity->setAwayTeamName($awayTeam['team_name'] ?? null);
                }

                $entity->setHomeTeamGoals($data['goalsHomeTeam'] ?? null);
                $entity->setAwayTeamGoals($data['goalsAwayTeam'] ?? null);
            }
            return $entity;
        } else {
            return null;
        }
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return new $type() instanceof Game;
    }
}
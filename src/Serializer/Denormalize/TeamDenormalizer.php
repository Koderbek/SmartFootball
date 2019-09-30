<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 20.08.2019
 * Time: 21:36
 */

namespace App\Serializer\Denormalize;


use App\Entity\League;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TeamDenormalizer implements DenormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (key_exists('team_id', $data)) {
            $entity = $this->em->getRepository(Team::class)->find($data['team_id']);
            if (!$entity) {
                $entity = new Team();
                $entity->setId($data['team_id']);
            }
        } else {
            throw new HttpException(400, "Bad Request");
        }
        $entity->setName($data['name'] ?? $entity->getName());
        if (key_exists('league_id', $data)){
            $league = $this->em->getRepository(League::class)->find($data['league_id']);
            $entity->setLeague($league);
        }

        return $entity;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return new $type() instanceof Team;
    }
}
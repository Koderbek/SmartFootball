<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 15.08.2019
 * Time: 23:12
 */

namespace App\Serializer\Denormalize;


use App\Entity\League;
use App\Entity\User;
use App\Entity\UserInterests;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserInterestsDenormalizer implements DenormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $entity = null;
        if (key_exists('id', $data)) {
            $entity = $this->em->getRepository(UserInterests::class)->find($data['id']);
        } elseif (!$entity) {
            $entity = new UserInterests();
        }

        $entity->setTeam($data['team'] ?? $entity->getTeam());

        if (key_exists('user', $data)){
            $user = $this->em->getRepository(User::class)->find($data['user']);
            $entity->setUser($user);
        }

        if (key_exists('league', $data)){
            $league = $this->em->getRepository(League::class)->find($data['league']);
            $entity->setLeague($league);
        }

        return $entity;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return new $type() instanceof UserInterests;
    }

}
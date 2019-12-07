<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 30.11.2019
 * Time: 22:10
 */

namespace App\Serializer\Denormalize;


use App\Entity\League;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class LeagueDenormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface $em */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!key_exists('league_id', $data)){
            throw new HttpException(400, "Bad Request");
        }

        $entity = $this->em->getRepository(League::class)->find($data['league_id']);
        if ($entity) {
            return $entity;
        }

        $entity = new League();
        $entity->setId($data['league_id']);
        $entity->setName($data['name']);
        $entity->setCountry($data['country']);
        $entity->setLogo($data['logo']);

        return $entity;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return new $type() instanceof League;
    }

}
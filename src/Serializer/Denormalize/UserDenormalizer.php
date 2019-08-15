<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 15.08.2019
 * Time: 21:45
 */

namespace App\Serializer\Denormalize;


use App\Entity\User;
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

class UserDenormalizer implements DenormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (key_exists('id', $data)) {
            $entity = $this->em->getRepository(User::class)->find($data['id']);
            if (!$entity) {
                $entity = new User();
                $entity->setId($data['id']);
            }
        } else {
            throw new HttpException(400, "Bad Request");
        }
        $entity->setName($data['name'] ?? $entity->getName());

        return $entity;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return new $type() instanceof User;
    }

}
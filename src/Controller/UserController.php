<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 10.08.2019
 * Time: 22:52
 */

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class UserController
 * @package App\Controller
 *
 * @Route("/user")
 */
class UserController extends AbstractApiController
{
    /**
     * @Route("/", methods={"POST"})
     */
    public function new(SerializerInterface $serializer, Request $request, EntityManagerInterface $em)
    {
        $entity = $serializer->deserialize($request->getContent(), User::class,'json');
        $em->persist($entity);
        $em->flush();

        $json = $serializer->serialize($entity, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(SerializerInterface $serializer, User $user)
    {
        $json = $serializer->serialize($user, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }
}
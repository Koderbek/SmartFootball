<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 15.08.2019
 * Time: 23:37
 */

namespace App\Controller;


use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserInterests;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class UserInterestsController
 * @package App\Controller
 */
class UserInterestsController extends AbstractApiController
{
    /**
     * @Route("/user-interests/", methods={"POST"})
     */
    public function new(SerializerInterface $serializer, Request $request, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $entity = $serializer->deserialize($request->getContent(), UserInterests::class,'json');
        $em->persist($entity);
        $em->flush();

        $json = $serializer->serialize($entity, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/user-interests/{id}", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function show(Request $request, SerializerInterface $serializer, UserInterests $interests)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $json = $serializer->serialize($interests, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("user/{user}/interests/{team}", methods={"DELETE"})
     */
    public function delete(
        User $user,
        Team $team,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    )
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $interests = $em
            ->getRepository(UserInterests::class)
            ->findOneBy(['user' => $user->getId(), 'team' => $team->getId()]);
        $em->remove($interests);
        $em->flush();
        return $this->createResponse("Ok");
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 15.09.2019
 * Time: 14:13
 */

namespace App\Controller;


use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class TeamController
 * @package App\Controller
 *
 * @Route("/team")
 */
class TeamController extends AbstractApiController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $leagues = $em->getRepository(Team::class)->findAll();
        $json = $serializer->serialize($leagues, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function show(Request $request, SerializerInterface $serializer, Team $team)
    {
        $this->checkToken($request, $this->getParameter('api_key'));

        $json = $serializer->serialize($team, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }
}
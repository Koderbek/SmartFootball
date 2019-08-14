<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 14.08.2019
 * Time: 23:14
 */

namespace App\Controller;


use App\Entity\League;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class LeagueApiController
 * @package App\Controller
 *
 * @Route("/league")
 */
class LeagueApiController extends AbstractApiController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $leagues = $em->getRepository(League::class)->findAll();
        $json = $serializer->serialize($leagues, "json", ['groups' => ["show"]]);
        return $this->createResponse($json);
    }
}
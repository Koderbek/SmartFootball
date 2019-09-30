<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 10.08.2019
 * Time: 22:45
 */
namespace App\Controller;


use App\Helper\SecurityInterface;
use App\Helper\SecurityTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AbstractApiController extends Controller implements SecurityInterface
{
    use SecurityTrait;

    protected function createResponse($data, $code = 200, $headers = [])
    {
        if (!is_null($this->getUser())) {
            $this->getUser()->setLastRequestTime(date('U'));
            $this->getDoctrine()->getManager()->flush();
        }

        $response = new Response($data, $code, $headers);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
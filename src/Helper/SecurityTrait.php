<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 15.09.2019
 * Time: 13:23
 */

namespace App\Helper;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait SecurityTrait
{
    public function checkToken(Request $request, $rightToken)
    {
        $apiKey = $request->headers->get('apikey');
        if ($apiKey != $rightToken) {
            throw new HttpException(403, "Access Denied");
        }
    }
}
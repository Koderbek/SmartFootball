<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 15.09.2019
 * Time: 13:48
 */

namespace App\Helper;


use Symfony\Component\HttpFoundation\Request;

interface SecurityInterface
{
    public function checkToken(Request $request, $token);
}
<?php

namespace Wcom\Jwt;

use ReallySimpleJWT\Token;
use Wcom\Jwt\Facades\WordPress;
use Wcom\Jwt\App\JWT;
use Wcom\Jwt\App\Cookie;
use Wcom\Jwt\Route\PostLogin;
use Wcom\Jwt\Query\GetUserId;
use Exception;

class JWTAuth
{
    public function initRoutes($headerSecret, $cookieSecret)
    {
        $wp = new WordPress;
        $getUserId = new GetUserId;
        $jwt = new JWT;
        $cookie = new Cookie;

        Routes::wpAjaxToken($wp, $headerSecret);
        PostLogin::route($wp, $getUserId, $jwt, $cookie, $headerSecret, $cookieSecret);
        Routes::verify($wp, $headerSecret);
        Routes::lastTenPosts($wp, $headerSecret);
    }
}
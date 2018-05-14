<?php

namespace Wcom\Jwt;

use ReallySimpleJWT\Token;
use Wcom\Jwt\Facades\WordPress;
use Wcom\Jwt\App\JWT;
use Wcom\Jwt\App\Cookie;
use Wcom\Jwt\Route\PostLogin;
use Wcom\Jwt\Query\GetUserId;
use Exception;

class JsonAuth
{
    public static function initDefaultRoutes($headerSecret, $cookieSecret)
    {
        $wp = new WordPress;

        Routes::wpAjaxToken($wp, $headerSecret);
        Routes::login($wp, $headerSecret);
        Routes::verify($wp, $headerSecret);
        Routes::lastTenPosts($wp, $headerSecret);
    }

    /**
     * Verifies the token is valid
     * @return int|bool Int for userId or bool for false
     */
    public static function verify($token, $secret)
    {
        try {
            $result = Token::validate($token, $secret);
        } catch (Exception $e) {
            return false;
        }

        if (!$result) {
            return false;
        }

        $data = Token::getPayload($token);
        $decoded = json_decode($data);

        return isset($decoded->user_id);

    }

    public static function expiryDate()
    {
        return date('Y-m-d H:i:s', strtotime('+1 hour'));
    }

    /**
     * Checks the secret
     *
     * @param string $secret
     * @return int|bool
     */
    public static function check($secret)
    {
        return self::verify(self::getTokenFromRequest(), $secret);
    }

    /**
     * Gets token request
     *
     * @return string
     */
    public static function getTokenFromRequest()
    {
        $headers = apache_request_headers();
        $auth = explode("Bearer ", $headers['Authorization']);
        return $auth[1];
    }
}
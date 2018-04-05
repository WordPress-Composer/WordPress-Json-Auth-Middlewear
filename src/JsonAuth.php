<?php

namespace Wcom\Jwt;

use ReallySimpleJWT\Token;
use Exception;

class JsonAuth
{
    public static function initDefaultRoutes($secret)
    {
        Routes::wpAjaxToken($secret);
        Routes::login($secret);
        Routes::verify($secret);
        Routes::lastTenPosts($secret);
    }

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

        return !isset($decoded->user_id);

    }

    public static function expiryDate()
    {
        return date('Y-m-d H:i:s', strtotime('+1 seconds'));
    }

    public static function check($secret)
    {
        return self::verify(self::getTokenFromRequest(), $secret);
    }

    public static function getTokenFromRequest()
    {
        $headers = apache_request_headers();
        $auth = explode("Bearer ", $headers['Authorization']);
        return $auth[1];
    }
}
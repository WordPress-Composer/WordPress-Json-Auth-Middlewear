<?php

namespace Wcom\Jwt;

use ReallySimpleJWT\Token;
use Exception;

class JsonAuth
{
    public static function verify(GetUser $getUser, $token, $secret)
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
        $user = $getUser->byId($decoded->user_id);

        if (!$user) {
            return false;
        }

        return true;

    }

    public static function expiryDate()
    {
        return date('Y-m-d H:i:s', strtotime('+30 minutes'));
    }

    public static function check($secret)
    {
        return self::verify(new GetUser, self::getTokenFromRequest(), $secret);
    }

    public static function getTokenFromRequest()
    {
        $headers = apache_request_headers();
        $auth = explode("Bearer ", $headers['Authorization']);
        return $auth[1];
    }
}
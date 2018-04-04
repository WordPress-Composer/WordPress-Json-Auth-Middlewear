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
        //$user = get_user_by('id', $decoded->user_id);

        if (!$user) {
            return false;
        }

        return true;

    }
}
<?php

namespace Wcom\Jwt\Domain;

use Exception;

class AccessToken
{

    private $token;

    public static function define($accessToken)
    {
        if (!is_string($accessToken)) {
            throw new Exception('Access token should be a string');
        }

        if (!self::isJWTStructure($accessToken)) {
            throw new Exception('Access token is not a valid JWT');
        }

        return new static($accessToken);
    }

    private static function isJWTStructure($value)
    {
        $testing = explode('.', $value);

        if (count($testing) !== 3) {
            return false;
        }

        $shortParts = array_filter($testing, function($value) {
            return strlen($value) <= 8;
        });

        return count($shortParts) === 0;
    }

    private function __construct($token)
    {
        $this->token = $token;
    }

    public function val()
    {
        return $this->token;
    }
}
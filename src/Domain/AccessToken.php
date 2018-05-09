<?php

namespace Wcom\Jwt\Domain;

use Exception;

/**
 * Access token
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AccessToken
{

    private $token;

    /**
     * Defines the access token
     *
     * @param string $accessToken
     * @return AccessToken
     */
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

    /**
     * Checks if has JWT structure
     *
     * @param string $value
     * @return boolean
     */
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

    /**
     * Gets the AccessToken string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->token;
    }

    /**
     * Gets the AccessToken value
     *
     * @return string
     */
    public function val()
    {
        return $this->token;
    }
}
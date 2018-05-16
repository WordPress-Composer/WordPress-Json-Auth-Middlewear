<?php

namespace Wcom\Jwt\Domain;

/**
 * Cookie token
 * @author Gemma Black <gblackuk@gmail.com>
 */
class CookieToken extends AccessToken
{

    /**
     * Defines the access token
     *
     * @param string $accessToken
     * @return CookieToken
     */
    public static function define($accessToken)
    {
        if (!is_string($accessToken)) {
            throw new DomainException('Cookie token should be a string');
        }

        if (!self::isJWTStructure($accessToken)) {
            throw new DomainException('Cookie token is not a valid JWT');
        }

        return new static($accessToken);
    }
}

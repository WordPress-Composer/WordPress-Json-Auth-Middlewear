<?php

namespace Wcom\Jwt\Domain;

/**
 * JWT interface
 * @author Gemma Black <gblackuk@gmail.com>
 */
interface JWT
{
    /**
     * Generates a JWT Token
     *
     * @param UserId $id
     * @param Secret $secret
     * @param ExpiryDate $expiryDate
     * @param HomeUrl $url
     * @return AccessToken
     */
    public function generate(UserId $id, Secret $secret, ExpiryDate $expiryDate, HomeUrl $url);
}

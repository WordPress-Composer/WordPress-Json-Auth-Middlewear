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
     * @param ExpiryDate $expiryDate
     * @param HomeUrl $url
     * @param Secret $headerSecret
     * @param Secret $cookieSecret
     * @return DoubleToken
     * @throws DomainException
     */
    public function generate(
        UserId $id, 
        ExpiryDate $expiryDate, 
        HomeUrl $url, 
        Secret $headerSecret, 
        Secret $cookieSecret
    );

    /**
     * Verifies the access token
     *
     * @param DoubleToken $doubleToken
     * @param Secret $secret
     * @return bool
     * @throws DomainException
     */
    public function verify(DoubleToken $doubleToken, Secret $secret);
}

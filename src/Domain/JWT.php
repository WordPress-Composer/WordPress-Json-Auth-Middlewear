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
     * @throws DomainException
     */
    public function generate(UserId $id, Secret $secret, ExpiryDate $expiryDate, HomeUrl $url);

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

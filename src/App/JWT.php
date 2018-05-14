<?php

namespace Wcom\Jwt\App;

use Wcom\Jwt\Domain\JWT as iJWT;
use Wcom\Jwt\Domain\UserId;
use Wcom\Jwt\Domain\Secret;
use Wcom\Jwt\Domain\ExpiryDate;
use Wcom\Jwt\Domain\HomeUrl;
use Wcom\Jwt\Domain\AccessToken;
use Wcom\Jwt\Domain\DoubleToken;
use Wcom\Jwt\Domain\DomainException;
use ReallySimpleJWT\Token;
use Exception;

class JWT implements iJWT
{


    /**
     * Generates a JWT Token
     *
     * @param UserId $userId
     * @param Secret $secret
     * @param ExpiryDate $expiryDate
     * @param HomeUrl $url
     * @return AccessToken
     * @throws DomainException
     */
    public function generate(UserId $userId, Secret $secret, ExpiryDate $expiryDate, HomeUrl $url)
    {
        try {
            $token = Token::getToken($userId, $secret, $expiryDate, $url);
            return AccessToken::define($token);
        } catch (Exception $e) {
            error_log($e);
            throw new DomainException('Could not generate Token');
        }
    }

    /**
     * Verifies the access token
     *
     * @param DoubleToken $doubleToken
     * @param Secret $secret
     * @return bool
     */
    public function verify(DoubleToken $doubleToken, Secret $secret)
    {
        try {
            $cookie = Token::validate($doubleToken->cookie(), $secret);
            $header = Token::validate($doubleToken->header(), $secret);
            return $cookie && $header;
        } catch (Exception $e) {
            throw new DomainException('Could not verify token');
        }
    }
}

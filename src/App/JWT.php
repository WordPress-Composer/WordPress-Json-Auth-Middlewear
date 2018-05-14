<?php

namespace Wcom\Jwt\App;

use Wcom\Jwt\Domain\JWT as iJWT;
use Wcom\Jwt\Domain\UserId;
use Wcom\Jwt\Domain\Secret;
use Wcom\Jwt\Domain\ExpiryDate;
use Wcom\Jwt\Domain\HomeUrl;
use Wcom\Jwt\Domain\AccessToken;
use Wcom\Jwt\Domain\DoubleToken;
use ReallySimpleJWT\Token;
use Exception;

class JWT implements iJWT
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
    )
    {
        try {
            $headerToken = Token::getToken($id, $headerSecret, $expiryDate, $url);
            $cookieToken = Token::getToken($id, $cookieSecret, $expiryDate, $url);
        } catch (Exception $e) {
            error_log($e);
            throw new AppException('Could not generate Token');
        }

        return DoubleToken::accept(
            AccessToken::define($cookieToken),
            AccessToken::define($headerToken)
        );
         
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
            throw new AppException('Could not verify token');
        }
    }
}

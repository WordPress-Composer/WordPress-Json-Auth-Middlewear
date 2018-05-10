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
     * @param UserId $userId
     * @param Secret $secret
     * @param ExpiryDate $expiryDate
     * @param HomeUrl $url
     * @todo Implement
     * @return AccessToken
     */
    public function generate(UserId $userId, Secret $secret, ExpiryDate $expiryDate, HomeUrl $url)
    {
        //return Token::getToken($userId, $secret, $expiryDate, $url);
        return AccessToken::define('aaaaaaa.bbbbbbbb.cccccccc');
    }

    /**
     * Verifies the access token
     *
     * @param DoubleToken $doubleToken
     * @param Secret $secret
     * @todo Implement
     * @return bool
     */
    public function verify(DoubleToken $doubleToken, Secret $secret)
    {
        return false;
    }
}

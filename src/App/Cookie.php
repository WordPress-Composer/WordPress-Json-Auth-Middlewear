<?php

namespace Wcom\Jwt\App;

use Wcom\Jwt\Domain\Cookie as iCookie;
use Wcom\Jwt\Domain\AccessToken;
use ParagonIE\Cookie\Cookie as ParagonCookie;
use Exception;

/**
 * Cookie utils
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Cookie implements iCookie
{
    /**
     * Save access token into cookie
     *
     * @param AccessToken $token
     * @return void
     */
    public function save(AccessToken $token)
    {
        try {
            $cookie = new ParagonCookie('wcom_jwt');
            $cookie->setValue($token->val());
            $cookie->setHttpOnly(true);
            $cookie->save();
        } catch (Exception $e) {
            error_log($e);
            throw new AppException('Could not save cookie token');
        }
    }
}
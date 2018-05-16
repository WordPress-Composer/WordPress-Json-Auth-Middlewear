<?php

namespace Wcom\Jwt;

use Wcom\Jwt\Framework\Router;

/**
 * JWT Auth main
 * @author Gemma Black <gblackuk@gmail.com>
 */
class JWTAuth
{
    /**
     * Initialise routes
     *
     * @param string $headerSecret
     * @param string $cookieSecret
     * @return void
     */
    public static function initRoutes($headerSecret, $cookieSecret)
    {
        $router = new Router('wcom/jwt/v1');

        $router->post('/action/authenticate', 'Action@authenticate', [
            'headerSecret' => $headerSecret,
            'cookieSecret' => $cookieSecret
        ]);

        $router->post('/action/verify', 'Action@verify', [
            'headerSecret' => $headerSecret,
            'cookieSecret' => $cookieSecret
        ]);
    }
}
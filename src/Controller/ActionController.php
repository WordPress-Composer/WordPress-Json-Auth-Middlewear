<?php

namespace Wcom\Jwt\Controller;

use Wcom\Jwt\App\JWT;
use Wcom\Jwt\App\Cookie;
use Wcom\Jwt\Query\GetUserId;
use Wcom\Jwt\Route\PostLogin;
use Wcom\Jwt\Facades\WordPress;
use Wcom\Jwt\Domain\UserId;
use Wcom\Jwt\Domain\Secret;
use Wcom\Jwt\Domain\HomeUrl;
use Wcom\Jwt\Domain\ExpiryDate;
use Wcom\Jwt\Domain\DoubleToken;
use Wcom\Jwt\Domain\Username;
use Wcom\Jwt\Domain\Password;
use Wcom\Jwt\Domain\AccessToken;
use Wcom\Jwt\Framework\RouteRequest;
use Wcom\Jwt\Framework\Router;
use DateTime;
use WP_REST_Request;
use Exception;

/**
 * Action controller
 * @author Gemma Black <gblackuk@gmail.com>
 */
class ActionController
{
    private $wp;
    private $getUserId;
    private $jwt;
    private $cookie;

    public function __construct()
    {
        $this->wp = new WordPress;
        $this->getUserId = new GetUserId;
        $this->jwt = new JWT;
        $this->cookie = new Cookie;
    }

    /**
     * Authentication controller
     *
     * @param array $params
     * @param array $data
     * @return void
     */
    public function authenticate($params = [], $data = [])
    {
        $headerSecret = Secret::set($data['headerSecret']);
        $cookieSecret = Secret::set($data['cookieSecret']);
        $username = Username::set($params['username']);
        $password = Password::set($params['password']);
        $expiryDate = ExpiryDate::from(new DateTime, 20);
        $homeUrl = HomeUrl::set($this->wp->homeUrl());

        // Get user id or burn!
        $userId = $this->getUserId->fromCredentials($username, $password);
        
        // Dependent on JWT generation
        $doubleToken = $this->jwt->generate($userId, $expiryDate, $homeUrl, $headerSecret, $cookieSecret);
        
        // Save cookie
        $this->cookie->save($doubleToken->cookie());

        // Return response
        $this->wp->sendJson([
            'token' => $doubleToken->header()->val()
        ]);
    }
}
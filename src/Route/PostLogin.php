<?php 

namespace Wcom\Jwt\Route;

use Wcom\Jwt\Facades\WordPress;
use Wcom\Jwt\Domain\JWT;
use Wcom\Jwt\Domain\UserId;
use Wcom\Jwt\Domain\Secret;
use Wcom\Jwt\Domain\HomeUrl;
use Wcom\Jwt\Domain\ExpiryDate;
use Wcom\Jwt\Domain\DoubleToken;
use Wcom\Jwt\Domain\Username;
use Wcom\Jwt\Domain\Password;
use Wcom\Jwt\Domain\AccessToken;
use Wcom\Jwt\Domain\GetUserId;
use Wcom\Jwt\Domain\Cookie;
use Wcom\Jwt\Framework\RouteRequest;
use DateTime;
use WP_REST_Request;
use Exception;

/**
 * Login route (POST)
 * Gemma Black <gblackuk@gmail.com>
 * @todo Hide WP_REST_Request into a Request object that's not related to WordPress
 */
class PostLogin
{
    /**
     * Undocumented function
     *
     * @param WordPress $wp
     * @param GetUserId $getUserId
     * @param JWT $jwt
     * @param Cookie $cookie
     * @param string $headerSecret
     * @param string $cookieSecret
     * @todo use a parameter object with the builder pattern to pass in configurations
     * @return void
     */
    public static function route(
        WordPress $wp, 
        GetUserId $getUserId, 
        JWT $jwt, 
        Cookie $cookie,
        $headerSecret, 
        $cookieSecret
    )
    {
        $wp->addAction('rest_api_init', function() use ($wp, $getUserId, $jwt, $cookie, $headerSecret, $cookieSecret) {
            $wp->registerRestRoute('wcom/jwt/v1', '/action/login', [
                'methods' => 'POST',
                'callback' => RouteRequest::handle(function(WP_REST_Request $request) use ($wp, $getUserId, $jwt, $cookie, $headerSecret, $cookieSecret) {

                    // Validate parameters and external data into domain objects
                    $headerSecret = Secret::set($headerSecret);
                    $cookieSecret = Secret::set($cookieSecret);
                    $username = Username::set($request->get_param('username'));
                    $password = Password::set($request->get_param('password'));
                    $expiryDate = ExpiryDate::from(new DateTime, 20);
                    $homeUrl = HomeUrl::set($wp->homeUrl());

                    // Get user id or burn!
                    $userId = $getUserId->fromCredentials($username, $password);
                    
                    // Dependent on JWT generation
                    $doubleToken = $jwt->generate($userId, $expiryDate, $homeUrl, $headerSecret, $cookieSecret);
                    
                    // Save cookie
                    $cookie->save($doubleToken->cookie());

                    // Return response
                    $wp->sendJson([
                        'token' => $doubleToken->header()->val()
                    ]);
                })
            ]);
        });
    }
}
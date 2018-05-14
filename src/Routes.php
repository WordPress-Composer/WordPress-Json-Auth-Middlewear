<?php

namespace Wcom\Jwt;

use ReallySimpleJWT\Token;
use Wcom\Jwt\JsonAuth;
use Wcom\Jwt\Facades\WordPress;
use Wcom\Jwt\App\JWT;
use Exception;

class Routes
{

    public static function wpAjaxToken(WordPress $wp, $secret)
    {
        $wp->addAction('wp_ajax_wcom_json_auth_token', function() use ($secret, $wp) {
            $auth = $wp->currentUser();
        
            if ($wp->isError($auth)) {
                $wp->sendJsonError($auth->get_error_data(), 400);
            }
            
            $token = Token::getToken(
                $auth->ID, 
                $secret, 
                JsonAuth::expiryDate(), 
                $wp->homeUrl()
            );
        
            $wp->sendJson([
                'token' => $token   
            ]);
        });
    }

    public static function login(WordPress $wp, $secret)
    {
        $wp->addAction('rest_api_init', function() use ($secret, $wp) {
            $wp->registerRestRoute('wcom/jwt/v1', '/action/login', [
                'methods' => 'POST',
                'callback' => function($request) use ($secret, $wp) {
                    
                    $username = $request->get_param('username');
                    $password = $request->get_param('password');
        
                    $auth = $wp->authenticate($username, $password);
                    
                    if ($wp->isError($auth)) {
                        $wp->sendJsonError($auth->errorData(), 400);
                    }
        
                    $token = Token::getToken(
                        $auth->ID, 
                        $secret, 
                        JsonAuth::expiryDate(), 
                        $wp->homeUrl()
                    );
        
                    $wp->sendJson([
                        'token_type' => 'bearer',
                        'token' => $token   
                    ]);
                }
            ]);
        });
    }

    public static function verify(WordPress $wp, $secret)
    {
        $wp->addAction('rest_api_init', function() use ($secret, $wp) {
            $wp->registerRestRoute('wcom/jwt/v1', '/verify', [
                'methods' => 'GET',
                'callback' => function($request) use ($secret, $wp) {
                    $auth = JsonAuth::check($secret);
        
                    if (!$auth) {
                        $wp->sendJsonError('Invalid token', 400);
                    } else {
                        $wp->sendJson(['success' => true]);
                    }
                }
            ]);
        });
    }

    public static function lastTenPosts(WordPress $wp, $secret)
    {
        $wp->addAction('rest_api_init', function() use ($secret, $wp) {
            $wp->registerRestRoute('wcom/jwt/v1', '/posts', [
                'methods' => 'GET',
                'callback' => function($request) use ($secret, $wp) {
                    
                    $auth = JsonAuth::check($secret);
        
                    if (!$auth) {
                        $wp->sendJsonError([], 400);
                    } else {
                        $wp->sendJson([
                            'success' => true, 
                            'posts' => $wp->posts([
                                'post_status' => ['any'],
                                'posts_per_page' => 10
                            ])
                        ]);
                    }
                }
            ]);
        });
    }
}
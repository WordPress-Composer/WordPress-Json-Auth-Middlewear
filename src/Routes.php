<?php

namespace Wcom\Jwt;

use ReallySimpleJWT\Token;
use Wcom\Jwt\JsonAuth;
use WP_REST_Request;

class Routes
{
    public static function wpAjaxToken($secret)
    {
        add_action('wp_ajax_wcom_json_auth_token', function() use ($secret) {
            $auth = wp_get_current_user();
        
            if (is_wp_error($auth)) {
                wp_send_json_error($auth->get_error_data(), 400);
            }
            
            $token = Token::getToken(
                $auth->ID, 
                $secret, 
                JsonAuth::expiryDate(), 
                get_home_url()
            );
        
            wp_send_json([
                'token' => $token   
            ]);
        });
    }

    public static function login($secret)
    {
        add_action('rest_api_init', function() use ($secret) {
            register_rest_route('wcom/jwt/v1', '/action/login', [
                'methods' => 'POST',
                'callback' => function(WP_REST_Request $request) use ($secret) {
                    
                    $username = $request->get_param('username');
                    $password = $request->get_param('password');
        
                    $auth = wp_authenticate($username, $password);
                    
                    if (is_wp_error($auth)) {
                        return wp_send_json_error($auth->get_error_data(), 400);
                    }
        
                    $token = Token::getToken(
                        $auth->ID, 
                        $secret, 
                        JsonAuth::expiryDate(), 
                        get_home_url()
                    );
        
                    wp_send_json([
                        'token_type' => 'bearer',
                        'token' => $token   
                    ]);
                }
            ]);
        });
    }

    public static function verify($secret)
    {
        add_action('rest_api_init', function() use ($secret) {
            register_rest_route('wcom/jwt/v1', '/verify', [
                'methods' => 'GET',
                'callback' => function(WP_REST_Request $request) use ($secret) {
                    $auth = JsonAuth::check($secret);
        
                    if ($auth instanceof Exception) {
                        wp_send_json_error($auth->getMessage(), 400);
                    } else {
                        wp_send_json(['success' => true]);
                    }
                }
            ]);
        });
    }

    public static function lastTenPosts($secret)
    {
        add_action('rest_api_init', function() use ($secret) {
            register_rest_route('wcom/jwt/v1', '/posts', [
                'methods' => 'GET',
                'callback' => function(WP_REST_Request $request) use ($secret) {
                    
                    $auth = JsonAuth::check($secret);
        
                    if (!$auth) {
                        wp_send_json_error([], 400);
                    } else {
                        wp_send_json([
                            'success' => true, 
                            'posts' => get_posts([
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
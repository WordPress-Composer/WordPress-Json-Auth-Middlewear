<?php 

/**
 * Plugin Name: WordPress JWT Rest API Middlewear
 * Description: WordPress middlewear composer plugin that uses JWTs
 * Author: Gemma Black <gblackuk@gmail.com>
 */

/**
 * Prototype
 * @todo rewrite properly
 */

use ReallySimpleJWT\Token;
use Wcom\Jwt\JsonAuth;
use Wcom\Jwt\GetUser;

$secret = 'MY_SEcRET_123!';

add_action('wp_ajax_wcom_json_auth_token', function() use ($secret) {
    $auth = wp_get_current_user();

    if (is_wp_error($auth)) {
        wp_send_json_error($auth->get_error_data(), 400);
    }

    $now = JsonAuth::expiryDate();
    
    $token = Token::getToken($auth->ID, $secret, $now, get_home_url());

    wp_send_json([
        'token' => $token   
    ]);
});

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

            $now = JsonAuth::expiryDate();
            $token = Token::getToken($auth->ID, $secret, $now, get_home_url());

            return [
                'token' => $token   
            ];
        }
    ]);
});

add_action('rest_api_init', function() use ($secret) {
    register_rest_route('wcom/jwt/v1', '/verify', [
        'methods' => 'GET',
        'callback' => function(WP_REST_Request $request) use ($secret) {
            $headers = apache_request_headers();
            $auth = explode("Bearer ", $headers['Authorization']);
            $token = $auth[1];
            $result = JsonAuth::verify(new GetUser, $token, $secret);

            if (!$result) {
                wp_send_json_error([], 400);
            } else {
                wp_send_json(['success' => true]);
            }
        }
    ]);
});

add_action('rest_api_init', function() use ($secret) {
    register_rest_route('wcom/jwt/v1', '/posts', [
        'methods' => 'GET',
        'callback' => function(WP_REST_Request $request) use ($secret) {
            $headers = apache_request_headers();
            $auth = explode("Bearer ", $headers['Authorization']);
            $token = $auth[1];
            $result = JsonAuth::verify(new GetUser, $token, $secret);

            if (!$result) {
                wp_send_json_error([], 400);
            } else {
                wp_send_json([
                    'success' => true, 
                    'posts' => get_posts([
                        'post_status' => ['draft']
                    ])
                ]);
            }
        }
    ]);
});
<?php 

/**
 * Plugin Name: WordPress JWT Rest API Middlewear
 * Description: WordPress middlewear composer plugin that uses JWTs
 * Author: Gemma Black <gblackuk@gmail.com>
 */

use ReallySimpleJWT\Token;

add_action('rest_api_init', function() {
    register_rest_route('wcom/jwt/v1', '/action/login', [
        'methods' => 'POST',
        'callback' => function(WP_REST_Request $request) {

            $secret = 'MY_SEcRET_123!';
            
            $username = $request->get_param('username');
            $password = $request->get_param('password');

            $auth = wp_authenticate($username, $password);
            
            if (is_wp_error($auth)) {
                return wp_send_json_error($auth->get_error_data(), 400);
            }

            $now = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            $token = Token::getToken($auth->ID, $secret, $now, get_home_url());

            return [
                'token' => $token   
            ];
        }
    ]);
});
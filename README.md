# WordPress JWT Authorization Middlewear

> Currently in progress. Not ready for production.

WordPress has an awesome Rest API interface that allows us to create custom endpoints. However, custom endpoints that require authentication aren't part of their Rest API. Instead,
they have advised we use [plugins](https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/) to achieve this.

This plugin:

* Provides a simple API for developers to create custom end points and have authentication with a JWT system.
* Allows logins from outside the WordPress dashboard which returns a JWT token
* Allows already logged in users to get a JWT token through the wp_ajax system
* Designed to be used with Composer
* Does not require a GUI and is solely a code-based solution

## Current Issues/Decisions

* Whether to make this a WordPress Composer plugin or a simple Composer package.

## Requirements

* Composer
* WordPress install (works well with a [WordPress Composer](https://github.com/gemmadlou/WordPress-Composer-Starter) project)
* A PHP server development environment
* cURL

## Installation

Instructions coming soon.

## Login

This allows you to log into WordPress from outside the WordPress dashboard.

##### Method
```
POST
```

##### Route
```
/wp-json/wcom/jwt/v1/action/login 
```

##### Parameters
```
username : string
admin : string
```

#### Example

```shell
curl -X POST http://192.168.74.100/wp-json/wcom/jwt/v1/action/login \
    -H "Content-Type: application/json" \
    -d '{"username": "admin", "password": "admin"}' \
    | python -m json.tool
```

#### Response

```json
{
    "token": "aaaaaaaaaa.bbbbbbbbbb.ccccccccc"
}
```

## Get Token From Within WordPress Admin

This grabs a token that you can use within the WordPress dashboard. This uses WordPress' wp_ajax
rather than their Rest API. This means, the current user would be logged in.

```javascript
jQuery.post(ajaxurl, {
  'action': 'wcom_json_auth_token'
}, function(response) {
	console.log(response)
})
```

## Verify

This will verify your JWT, regardless of how you attained it (via login or wp_ajax).

##### Method
```
GET
```

##### Route
```
/wp-json/wcom/jwt/v1/verify
```

#### Example

```shell
curl -X GET "http://192.168.74.100/wp-json/wcom/jwt/v1/verify" \
    -H "Authorization: Bearer $TOKEN" \
    | python -m json.tool
```

#### Response

```json
{
    "success": true
}
```

## Check authorisation

Users should send the JWT through an Authentication Bearer header. 

```
"Authorization: Bearer $TOKEN"
```

To check whether a submitted JWT is verified within your code, use the following: 

```php
<?php

JsonAuth::check($secret)
```

#### Example: getting all posts including private and unpublished ones

Add the following to your functions.php or an alternative location.

```
> /theme/functions.php
```

```php
<?php

use Wcom\Jwt\JsonAuth;

add_action('rest_api_init', function() use ($secret) {
    register_rest_route('wcom/jwt/v1', '/posts', [
        'methods' => 'GET',
        'callback' => function(WP_REST_Request $request) use ($secret) {
            
            $result = JsonAuth::check($secret);

            if (!$result) {
                wp_send_json_error([], 400);
            } else {
                wp_send_json([
                    'success' => true, 
                    'posts' => get_posts([
                        'post_status' => ['any']
                    ])
                ]);
            }
        }
    ]);
});
```

```
curl -X GET http://192.168.74.100/wp-json/wcom/jwt/v1/posts \
    -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJpc3MiOiJodHRwOlwvXC8xOTIuMTY4Ljc0LjEwMCIsImV4cCI6IjIwMTgtMDQtMDQgMTc6MjY6MjAiLCJzdWIiOiIiLCJhdWQiOiIifQ.gFJupqx4hRACqWtZoKYjDCOepd8WZcKvtQgLf_U2578" 
```

## Todo

* Refresh token
* Interface to react with plugin

## Important Notes:

* Do not put sensitive information within the JWT Token.
* Authorization headers were used instead of cookies after working with Digital Ocean's v2 API, which sets a good standard.
* Use HTTPS for extra security.
* Do not store JWT tokens in localStorage unless you are prepared to add further verification methods to prevent CSRF attacks. I've yet clue myself up on this yet, but this video might be a good start: https://www.youtube.com/watch?v=2uvrGQEy8i4

## Resources

[Simple JWT Auth Flow](https://medium.freecodecamp.org/how-to-make-authentication-easier-with-json-web-token-cc15df3f2228)

[Refresh Tokens](https://auth0.com/blog/refresh-tokens-what-are-they-and-when-to-use-them/)

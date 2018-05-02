# WordPress JWT Authorization

<br>

> Currently in progress.

#### The problem

We have to use wp_ajax to make user authorized requests. However, can't we use the WordPress Rest API
to make authorized requests too?

#### The solution

This plugin:

* Provides a simple API for developers to create custom WordPress Rest API end points with JWTs

* Allows logins from outside the WordPress dashboard which returns a JWT

* Allows already logged in users to get a JWT through the wp_ajax system

* Designed to be used with Composer

* Does not require a GUI and is solely a code-based solution

## Requirements

* Composer
* [WordPress Composer install](https://github.com/gemmadlou/WordPress-Composer-Starter) project)
* A PHP server development environment
* cURL

## Initialise the JWT token routing

This plugin is completely unopinionated about where you define the secret. It could go
in the .env, or you could choose to put it within the database.

> A secret must have 
> - 12 characters
> - lower cases
> - upper cases
> - a number
> - a symbol.

```
> themes/theme-folder/functions.php
```
```php
<?php

use Wcom\Jwt\JsonAuth;

JsonAuth::initDefaultRoutes('MY_SEcRET_123!');
```

## Login

This allows you to log into WordPress from outside the WordPress dashboard.

##### # Method
```
POST
```

##### # Route
```
/wp-json/wcom/jwt/v1/action/login 
```

##### # Parameters
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

## Generate a token for a user that's already logged in

This grabs a token that you can use within the WordPress dashboard. This uses WordPress' wp_ajax
rather than their Rest API. This means, the current user would be logged in.

#### jQuery example

```javascript
jQuery.post(ajaxurl, {
  'action': 'wcom_json_auth_token'
}, function(response) {
	console.log(response)
})
```

#### Fetch example

```javascript
var formData = new FormData();
formData.append('action', 'wcom_json_auth_token');

fetch('http://192.168.74.100/wordpress/wp-admin/admin-ajax.php', {
  method: 'POST',
  body: formData,
  credentials: 'include'
})
.catch(error => console.error('Error:', error))
.then(response => console.log('Success:', response));
```

> `credentials: 'include'` ensures that cookies in the user's browser,
> set by WordPress are included in the ajax request.

## Verify

This will verify your JWT, regardless of how you attained it (via login or wp_ajax).

##### # Method
```
GET
```

##### # Route
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

## Check authorisation for new end points

When you create a new endpoint that requires authorisation, send the generated JWT 
through an Authentication Bearer header.

```
"Authorization: Bearer $TOKEN"
```

To check whether a submitted JWT is correct, verify as follows: 

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
    register_rest_route('wcom/jwt/v1', '/test', [
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

#### Test that your new route works

```
curl -X GET http://192.168.74.100/wp-json/wcom/jwt/v1/test \
    -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJpc3MiOiJodHRwOlwvXC8xOTIuMTY4Ljc0LjEwMCIsImV4cCI6IjIwMTgtMDQtMDQgMTc6MjY6MjAiLCJzdWIiOiIiLCJhdWQiOiIifQ.gFJupqx4hRACqWtZoKYjDCOepd8WZcKvtQgLf_U2578" 
```

## Roadmap

* [ ] Refresh token functionality, including saving tokens and creation date in database
* [ ] Create anti corruption layer to protect users of this plugin
* [ ] Correct error messages, dependent on validation
* [ ] Account for unhappy paths
* [ ] Create a Jekyll repo for this project (with better documentation instructions)

## Important Notes:

* Do not put sensitive information within the JWT Token.
* Authorization headers were used instead of cookies after working with Digital Ocean's v2 API, which sets a good standard.
* Use HTTPS for extra security.
* Do not store JWT tokens in localStorage unless you are prepared to add further verification methods to prevent CSRF attacks. I've yet clue myself up on this yet, but this video might be a good start: https://www.youtube.com/watch?v=2uvrGQEy8i4

## Contribute

Feel free to make a pull request or raise an issue.

## Resources

[Simple JWT Auth Flow](https://medium.freecodecamp.org/how-to-make-authentication-easier-with-json-web-token-cc15df3f2228)

[Refresh Tokens](https://auth0.com/blog/refresh-tokens-what-are-they-and-when-to-use-them/)

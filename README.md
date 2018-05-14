# WordPress JWT Authorization

<a href="https://codeclimate.com/github/WordPress-Composer/WordPress-Json-Auth-Middlewear/maintainability"><img src="https://api.codeclimate.com/v1/badges/1c3c74c309c54de51e59/maintainability" /></a>
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://github.com/WordPress-Composer/WordPress-Json-Auth-Middlewear/blob/master/LICENSE)

> In development

#### The problem

WordPress has a wp_ajax function that allows us to make authorized requests. However, this plugin allows developers to use Rest APIs and have authorisation.

#### The solution

This plugin:

* Has a low-level API to get JWT access tokens.

* Provides a simple solution for developers to create custom WordPress Rest API end points with JWTs.

* Allows logins from outside the WordPress dashboard which returns a JWT.

* Does not require a GUI and is solely a code-based solution.

## Roadmap

* [ ] Double access tokens [Issue #2](https://github.com/WordPress-Composer/WordPress-Json-Auth-Middlewear/issues/2)  
   This is for extra security. Using Authorization header tokens alone mean you're susceptible to XSS attacks. Using session cookies along mean your vulnerable to CSRF attacks. For more information read [here](http://www.redotheweb.com/2015/11/09/api-security.html).

* [ ] Publish a 0.0.1 version to Composer

* [ ] Create anti corruption layer to protect users of this plugin
   Protect the plugin from changes to the API. And also protect users by creating a standard outward facing API.

* [ ] Account for unhappy paths in code  
   Ensure correct errors are thrown and captured, and maybe implement a monadic sequence within a workflow.

* [ ] Create a Jekyll repo for this project (with better documentation instructions)
   The docs here look quite ugly. So instead, it'd be better to create a simple static-site repo, Jekyll, Gatsby or the like.

* [ ] Refresh token functionality, including saving tokens and creation date in database  
  It's considered a good solution to prevent users from having to log in everytime they return to the site. Depending on the upcoming use cases, this may be prioritized higher.

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
    -i \
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
    - i \
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
    -H "Authorization: Bearer $TOKEN"
```

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

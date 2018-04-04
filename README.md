# WordPress-Json-Auth-Middlewear

Wordpress JWT Middlewear

## Login

This allows you to log into WordPress from outside the WordPress dashboard.

##### Method
POST

##### Route
/wp-json/wcom/jwt/v1/action/login 

##### Parameters
username  
admin

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

This grabs a token that you can use within the WordPress dashboard. Sorry it's in jQuery,
but WordPress admin still uses jQuery and doesn't look to be removing it anytime soon. But feel free
to use fetch or axios etc.

```javascript
jQuery.post(ajaxurl, {
  'action': 'wcom_json_auth_token'
}, function(response) {
	console.log(response)
})
```

## Verify

This will verify your JWT is verified, regardless of whether you logged in to
get it, or created a token once you logged into the dashboard.

##### Method
GET

##### Route
/wp-json/wcom/jwt/v1/verify

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

## Resources

[Simple JWT Auth Flow](https://medium.freecodecamp.org/how-to-make-authentication-easier-with-json-web-token-cc15df3f2228)
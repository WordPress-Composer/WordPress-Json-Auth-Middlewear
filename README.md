# WordPress-Json-Auth-Middlewear

Wordpress JWT Middlewear

### # Login

| | |
|-|-|
| Method | POST | 
| Route | /wp-json/wcom/jwt/v1/action/login 
| Parameters | username |
| | admin |

#### Example

```bash
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

### # Verify

| | |
|-|-|
| Method | GET | 
| Route | /wp-json/wcom/jwt/v1/verify/:token

#### Example

```bash
curl http://192.168.74.100/wp-json/wcom/jwt/v1/verify/aaaaaaaaa.bbbbbbbbbb.ccccccccc

```

#### Response

```json
{
    "success": true
}
```

## Resources

[Simple JWT Auth Flow](https://medium.freecodecamp.org/how-to-make-authentication-easier-with-json-web-token-cc15df3f2228)
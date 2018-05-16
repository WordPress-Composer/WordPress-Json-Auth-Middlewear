<?php

use Wcom\Jwt\Domain\CookieToken;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class CookieTokenTest extends TestCase
{
    /**
     * Example JWT
     * @link https://scotch.io/tutorials/the-anatomy-of-a-json-web-token
     * @var string
     */
    public $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzY290Y2guaW8iLCJleHAiOjEzMDA4MTkzODAsIm5hbWUiOiJDaHJpcyBTZXZpbGxlamEiLCJhZG1pbiI6dHJ1ZX0.03f329983b86f7d9a9f5fef85305880101d5e302afafa20154d094b229f75773';
    
    /**
     * Example of short (invalid) JWT
     * @var string
     */
    public $tooShortJwt = 'cq7~ir-/@6H1/~}B[eb#b7=Ap|-"5c_5>K~_.vIf^;)6Q!I<Tu:)<s19@r#Hb"&a4k`k$C&l8167?\e6gRH_:"5YtOH;.~h<f]<Sz';

    public function test_is_settable()
    {
        $token = CookieToken::define($this->jwt);
        $this->assertEquals($this->jwt, $token->val());
    }

    /**
     * Tests the retrieval of the access token when it's a string
     * @return void
     */
    public function test_can_retrieve_access_token_as_string()
    {
        $token = CookieToken::define($this->jwt);
        $this->assertEquals($this->jwt, $token);
    }

    /**
     * Property tests only the acceptance of a string
     * @return void
     */
    public function test_only_accepts_strings1()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cookie token should be a string');
        CookieToken::define(123);
    }

    /**
     * Tests only the acceptance of a properly formatted string
     * @return void
     */
    public function test_only_accepts_properly_formatted_strings()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cookie token is not a valid JWT');
        CookieToken::define($this->tooShortJwt);
    }
}

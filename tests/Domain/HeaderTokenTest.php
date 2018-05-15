<?php

use Wcom\Jwt\Domain\HeaderToken;
use PHPUnit\Framework\TestCase;

class HeaderTokenTest
{
    /**
     * Example JWT
     * @link https://scotch.io/tutorials/the-anatomy-of-a-json-web-token
     * @var string
     */
    public $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzY290Y2guaW8iLCJleHAiOjEzMDA4MTkzODAsIm5hbWUiOiJDaHJpcyBTZXZpbGxlamEiLCJhZG1pbiI6dHJ1ZX0.03f329983b86f7d9a9f5fef85305880101d5e302afafa20154d094b229f75773';

    public function test_is_settable()
    {
        $token = HeaderToken::define($this->jwt);
        $this->assertEquals($this->jwt, $token);
    }
}

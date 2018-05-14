<?php 

use Wcom\Jwt\Domain\AccessToken;
use PHPUnit\Framework\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Access Token test
 * @author Gemma Black <gblackuk@googlemail.com>
 */
class AccessTokenTest extends TestCase
{

    use TestTrait;

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

    /**
     * Tests the checking of the access token
     * @return void
     */
    public function test_sets_access_token()
    {
        $token = AccessToken::define($this->jwt);
        $this->assertEquals($this->jwt, $token->val());
    }

    /**
     * Tests the retrieval of the access token when it's a string
     * @return void
     */
    public function test_can_retrieve_access_token_as_string()
    {
        $token = AccessToken::define($this->jwt);
        $this->assertEquals($this->jwt, $token);
    }

    /**
     * Property tests only the acceptance of a string
     * @return void
     */
    public function test_only_accepts_strings1()
    {
        $this
            ->forAll(
                Generator\int()
            )
            ->then(function($int) {
                try {
                    AccessToken::define($int);
                    $this->fail('This call should raise exception! ' . $int);
                } catch (Exception $e) {
                    $this->assertEquals(
                        'Access token should be a string',
                        $e->getMessage()
                    );
                }
            });
    }

    /**
     * Tests only the acceptance of a string
     * @return void
     */
    public function test_only_accepts_string2()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Access token should be a string');
        AccessToken::define([]);
    }
    
    /**
     * Tests only the acceptance of a properly formatted string
     * @return void
     */
    public function test_only_accepts_properly_formatted_strings()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Access token is not a valid JWT');
        AccessToken::define($this->tooShortJwt);
    }
}

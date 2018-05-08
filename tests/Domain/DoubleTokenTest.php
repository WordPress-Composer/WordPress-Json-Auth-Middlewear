<?php

use Wcom\Jwt\Domain\DoubleToken;
use Wcom\Jwt\Domain\AccessToken;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;
use TypeError;

class DoubleTokenTest extends TestCase
{

    private $jwt1 = 'aaaaaaaaa.bbbbbbbbbb.ccccccccc';
    private $jwt2 = 'fffffffff.bbbbbbbbbb.ccccccccc';

    public function test_does_not_accept_non_access_tokens()
    {
        $this->expectException(TypeError::class);
        DoubleToken::accept($this->jwt1, $this->jwt2);
    }

    public function test_does_not_accept_access_tokens_with_same_value()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Access tokens cannot be the same');
        DoubleToken::accept(AccessToken::define($this->jwt1), AccessToken::define($this->jwt1));
    }

    public function test_sets_double_access_tokens_when_different()
    {
        $token = DoubleToken::accept(AccessToken::define($this->jwt1), AccessToken::define($this->jwt2));
        $this->assertEquals($this->jwt1, $token->cookie());
        $this->assertEquals($this->jwt2, $token->header());
    }
}
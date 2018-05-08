<?php 

use Wcom\Jwt\Domain\Secret;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class SecretTest extends TestCase
{
    public function test_secret_is_settable()
    {
        $secret = Secret::set('MySecret101!');
        $this->assertEquals('MySecret101!', $secret->val());
    }

    public function test_secret_must_be_string1()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Secret must be a string');
        Secret::set([]);
    }

    public function test_secret_must_be_string2()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Secret must be a string');
        Secret::set(123);
    }
}

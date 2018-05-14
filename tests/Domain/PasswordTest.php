<?php

use Wcom\Jwt\Domain\Password;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    private $password = 'admin';
    
    public function test_is_settable()
    {
        $password = Password::set($this->password);
        $this->assertEquals($this->password, $password);
    }

    public function test_must_be_a_string()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Password must be a string');
        $password = Password::set(123);
    }

    public function test_cannot_be_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Password cannot be empty');
        $password = Password::set('');
    }
}
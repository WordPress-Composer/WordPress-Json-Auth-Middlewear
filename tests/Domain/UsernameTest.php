<?php

use Wcom\Jwt\Domain\Username;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    private $username = 'admin';
    
    public function test_is_settable()
    {
        $username = Username::set($this->username);
        $this->assertEquals($this->username, $username);
    }

    public function test_must_be_a_string()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Username must be a string');
        $username = Username::set(123);
    }

    public function test_cannot_be_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Username cannot be empty');
        $username = Username::set('');
    }
}

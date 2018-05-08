<?php

use Wcom\Jwt\Domain\UserId;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function test_is_settable()
    {
        $id = UserId::fromInt(123);
        $this->assertEquals(123, $id->val());
    }

    public function test_fails_if_not_integer()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('UserId must be an integer');
        UserId::fromInt('123');
    }
}
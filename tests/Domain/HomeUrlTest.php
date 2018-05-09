<?php

use Wcom\Jwt\Domain\HomeUrl;
use Wcom\Jwt\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class HomeUrlTest extends TestCase
{
    public function test_is_settable()
    {
        $url = HomeUrl::set('http://example.com');
        $this->assertEquals('http://example.com', $url);
    }

    public function test_breaks_if_not_valid_url()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Url is not valid');
        HomeUrl::set('http://');
    }
}
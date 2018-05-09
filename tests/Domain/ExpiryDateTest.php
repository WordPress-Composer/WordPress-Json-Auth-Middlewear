<?php

use Wcom\Jwt\Domain\ExpiryDate;
use PHPUnit\Framework\TestCase;

class ExpiryDateTest extends TestCase
{
    public function test_expiry_date_is_settable()
    {
        $dateTime = new DateTime();
        $date = ExpiryDate::set($dateTime);
        $this->assertEquals($dateTime, $date->val());
    }

    public function test_only_accepts_dateTime()
    {
        $this->expectException(TypeError::class);
        ExpiryDate::set('2017-11-02 10:00:00');
    }

    public function test_returns_dateTime_as_string()
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:s', '2011-01-01T15:03:01');
        $date = ExpiryDate::set($dateTime);
        $this->assertEquals('2011-01-01 15:03:01', $date);
    }
}

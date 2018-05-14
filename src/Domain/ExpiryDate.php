<?php

namespace Wcom\Jwt\Domain;

use DateTime;
use DateInterval;

/**
 * Expiry date value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class ExpiryDate
{
    private $dateTime;

    /**
     * Sets the dateTime
     *
     * @param DateTime $time
     * @return ExpiryDate
     */
    public static function set(DateTime $time)
    {
        return new self($time);
    }

    /**
     * Create expiry from a DateTime
     *
     * @param DateTime $fromTime
     * @param int $minutesToExpiry
     * @return ExpiryDate
     */
    public static function from(DateTime $fromTime, $minutesToExpiry)
    {
        if (!is_integer($minutesToExpiry)) {
            throw new DomainException('Expiry time should be a number in minutes');
        }
        $minutes = new DateInterval('PT' . $minutesToExpiry . 'M');
        $fromTime->add($minutes);
        return new self($fromTime);
    }

    /**
     * Private constructor
     *
     * @param DateTime $time
     */
    private function __construct(DateTime $time)
    {
        $this->dateTime = $time;
    }

    public function __toString()
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    /**
     * Returns the expiry date value
     *
     * @return DateTime
     */
    public function val()
    {
        return $this->dateTime;
    }
}

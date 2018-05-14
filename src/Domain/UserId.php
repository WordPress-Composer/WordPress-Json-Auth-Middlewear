<?php

namespace Wcom\Jwt\Domain;

/**
 * User id value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class UserId
{
    private $id;

    /**
     * Generate from integer
     *
     * @param int $id
     * @return UserId
     */
    public static function fromInt($id)
    {
        if (!is_integer($id)) {
            throw new DomainException('UserId must be an integer');
        }
        return new self($id);
    }

    /**
     * Sets the userId privately
     *
     * @param int $id
     */
    private function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the userId value
     *
     * @return int
     */
    public function val()
    {
        return $this->id;
    }
}

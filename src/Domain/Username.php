<?php

namespace Wcom\Jwt\Domain;

/**
 * Username value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Username
{
    private $username;

    /**
     * Sets username (privately)
     *
     * @param string $username
     */
    private function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Sets the username
     *
     * @param string $username
     * @return Username
     */
    public static function set($username)
    {
        if (!is_string($username)) {
            throw new DomainException('Username must be a string');
        }

        if (strlen($username) === 0) {
            throw new DomainException('Username cannot be empty');
        }

        return new self($username);
    }

    /**
     * Converts username into a string magically
     *
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }
}
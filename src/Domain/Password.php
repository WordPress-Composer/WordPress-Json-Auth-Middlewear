<?php

namespace Wcom\Jwt\Domain;

/**
 * Password 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Password
{
    private $password;

    /**
     * Sets the password
     *
     * @param string $password
     */
    private function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Sets the password
     *
     * @param string $password
     * @return Password
     */
    public static function set($password)
    {
        if (!is_string($password)) {
            throw new DomainException('Password must be a string');
        }

        if ($password === '') {
            throw new DomainException('Password cannot be empty');
        }

        return new self($password);
    }

    /**
     * Gets password magically
     *
     * @return string
     */
    public function __toString()
    {
        return $this->password;
    }
}
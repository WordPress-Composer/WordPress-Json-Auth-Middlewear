<?php

namespace Wcom\Jwt\Domain;

/**
 * Secret value object 
 * @author Gemma Black <gblackuk@googlemail.com>
 */
class Secret
{

    private $secret;

    /**
     * Private constructor
     *
     * @param string $secret
     */
    private function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Sets a secret
     *
     * @param string $secret
     * @return Secret
     */
    public static function set($secret)
    {
        if (!is_string($secret)) {
            throw new DomainException('Secret must be a string');
        }

        return new self($secret);
    }

    /**
     * Gets value
     *
     * @return string
     */
    public function val()
    {
        return $this->secret;
    }
}

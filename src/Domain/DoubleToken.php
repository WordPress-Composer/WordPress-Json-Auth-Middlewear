<?php

namespace Wcom\Jwt\Domain;

/**
 * Double token creator to ensure there are 2 unique tokens
 * @author Gemma Black <gblackuk@googlemail.com>
 */
class DoubleToken
{
    /**
     * Cookie token
     *
     * @var AccessToken
     */
    private $cookieToken;

    /**
     * Header token
     *
     * @var AccessToken
     */
    private $headerToken;
    
    /**
     * Accepts two access tokens and returns a new DoubleToken
     *
     * @param AccessToken $cookieToken
     * @param AccessToken $headerToken
     * @return DoubleToken
     */
    public static function accept(AccessToken $cookieToken, AccessToken $headerToken)
    {
        if ($cookieToken == $headerToken) {
            throw new DomainException('Access tokens cannot be the same');
        }

        return new self($cookieToken, $headerToken);
    }

    /**
     * Private constructor
     *
     * @param AccessToken $cookieToken
     * @param AccessToken $headerToken
     */
    private function __construct(AccessToken $cookieToken, AccessToken $headerToken)
    {
        $this->cookieToken = $cookieToken;
        $this->headerToken = $headerToken;
    }

    /**
     * Gets cookie token
     *
     * @return AccessToken
     */
    public function cookie()
    {
        return $this->cookieToken;
    }

    /**
     * Gets header token
     *
     * @return AccessToken
     */
    public function header()
    {
        return $this->headerToken;
    }
}

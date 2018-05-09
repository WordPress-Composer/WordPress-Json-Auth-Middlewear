<?php

namespace Wcom\Jwt\Domain;

/**
 * Home url value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class HomeUrl
{
    private $url;

    /**
     * Private constructor
     *
     * @param string $url
     */
    private function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Sets a url
     *
     * @param string $url
     * @return HomeUrl
     */
    public static function set($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new DomainException('Url is not valid');
        }
    
        return new static($url);
    }

    /**
     * Returns a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->url;
    }
}

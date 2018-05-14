<?php

namespace Wcom\Jwt\Domain;

/**
 * Cookie util 
 * @author Gemma Black <gblackuk@gmail.com>
 */
interface Cookie
{
    /**
     * Saves a cookie 
     *
     * @param AccessToken $token
     * @return void
     */
    public function save(AccessToken $token);
}
<?php

namespace Wcom\Jwt\Domain;

interface GetUserId
{
    /**
     * Gets User Id from credentials
     *
     * @param Username $username
     * @param Password $password
     * @return UserId
     */
    public function fromCredentials(Username $username, Password $password);
}

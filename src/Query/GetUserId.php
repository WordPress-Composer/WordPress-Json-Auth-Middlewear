<?php

namespace Wcom\Jwt\Query;

use Wcom\Jwt\Domain\GetUserId as iGetUserId;
use Wcom\Jwt\Domain\Username;
use Wcom\Jwt\Domain\Password;
use Wcom\Jwt\Domain\UserId;
use Wcom\Jwt\Facades\WordPress;

/**
 * Get userId query 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class GetUserId implements iGetUserId
{
    /**
     * Get userId from credentials
     *
     * @param Username $username
     * @param Password $password
     * @return UserId
     */
    public function fromCredentials(Username $username, Password $password)
    {
        $wp = new WordPress;
        $user = $wp->authenticate($username, $password);
        
        if ($wp->isError($user)) {
            throw new QueryException('Could not authenticate user');
        }

        return UserId::fromInt($user->ID);
    }
}
<?php 

namespace Wcom\Jwt;

class GetUser
{
    public function byId($id)
    {
        return get_user_by('id', $id);
    }
}
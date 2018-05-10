<?php 

namespace Wcom\Jwt;

use Wcom\Jwt\Facades\WordPress;

class GetUser
{
    private $wp;

    public function __construct(WordPress $wp)
    {
        $this->wp = $wp;
    }

    public function byId($id)
    {
        return $this->wp->getUserBy('id', $id);
    }
}
<?php

namespace Wcom\Jwt\Facades;

class WP_Error
{

    /**
     * Gets error data
     *
     * @param string|int $code
     * @return mixed|WP_Error
     */
    public function errorData($code = '')
    {
        return get_error_data($code);
    }
}

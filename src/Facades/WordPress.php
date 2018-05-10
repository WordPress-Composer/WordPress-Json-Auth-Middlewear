<?php 

namespace Wcom\Jwt\Facades;

/**
 * WordPress facade/adapter
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class WordPress
{

    /**
     * add_action facade
     *
     * @param string $tag
     * @param callable $callable
     * @param integer $priority
     * @param integer $numberOfAcceptableArgs
     * @return void
     */
    public function addAction($tag, $callable, $priority = 10, $numberOfAcceptableArgs = 1)
    {
        add_action($tag, $callable, $priority, $numberOfAcceptableArgs);
    }

    /**
     * Registers rest route
     *
     * @param string $namespace
     * @param string $route
     * @param array $args
     * @param bool $override
     * @return bool
     */
    public function registerRestRoute($namespace, $route, $args = [], $override = false)
    {
        return register_rest_route($namespace, $route, $args, $override);
    }

    /**
     * Gets the current logged in user
     *
     * @return Either<WP_User|false>
     */
    public function currentUser()
    {
        $user = wp_get_current_user();
        return !$user ? $user : new WP_User($user);
    }

    /**
     * Is any error
     *
     * @param Either<WP_Error|mixed> $error
     * @return bool
     */
    public function isError($error)
    {
        return is_wp_error($error);
    }

    /**
     * Send error JSON
     *
     * @param mixed $data
     * @param int $statusCode
     * @return void
     */
    public function sendJsonError($data = null, $statusCode = null)
    {
        return wp_send_json_error($data, $statusCode);
    }

    /**
     * Undocumented function
     *
     * @param mixed $response
     * @param int $statusCode
     * @return void
     */
    public function sendJson($response, $statusCode = null)
    {
        return wp_send_json($response, $statusCode);
    }

    /**
     * Gets home url
     *
     * @param string $path
     * @param Either<string|null> $scheme eg. http, https, relative, rest
     * @return string
     */
    public function homeUrl($path = '', $scheme = null)
    {
        return get_home_url($path, $scheme);
    }

    /**
     * Authenticates user
     *
     * @param string $username
     * @param string $password
     * @return Either<WP_User|WP_Error>
     */
    public function authenticate($username, $password)
    {
        return wp_authenticate($username, $password);
    }

    /**
     * Gets posts
     *
     * @param array $args
     * @return array
     */
    public function posts(array $args)
    {
        return get_posts($args);
    }

    /**
     * Undocumented function
     *
     * @param string $field
     * @param int|string $value
     * @return Either<WP_User|false>
     */
    public function getUserBy($field, $value)
    {
        return get_user_by($field, $value);
    }
}

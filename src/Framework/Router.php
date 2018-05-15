<?php

namespace Wcom\Jwt\Framework;

use Wcom\Jwt\Facades\WordPress;
use WP_REST_Request;
use Exception;

class Router
{
    private $namespace;
    private $wp;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
        $this->wp = new WordPress;
    }

    public function post($route, $functionString, $callerDependencies = [])
    {
        $this->api($route, $functionString, 'POST', $callerDependencies);
    }

    public function get($route, $functionString, $callerDependencies = [])
    {
        $this->api($route, $functionString, 'GET', $callerDependencies);
    }

    public function put($route, $functionString, $callerDependencies = [])
    {
        $this->api($route, $functionString, 'PUT', $callerDependencies);
    }

    public function delete($route, $functionString, $callerDependencies = [])
    {
        $this->api($route, $functionString, 'DELETE', $callerDependencies);
    }

    public function api($route, $functionString, $method = 'GET', $callerDependencies = [])
    {
        $callable = $this->getController($functionString);
        $namespace = $this->namespace;
        
        $this->wp->addAction('rest_api_init', function() use ($route, $namespace, $callable, $method, $callerDependencies) {
            $this->wp->registerRestRoute($namespace, $route, [
                'methods' => $method,
                'callback' => RouteRequest::handle(function(WP_REST_Request $request) use ($callable, $callerDependencies) {
                    $class = 'Wcom\\Jwt\\Controller\\' . $callable[0] . 'Controller';
                    $instance = new $class;

                    call_user_func_array(
                        [$instance, $callable[1]], 
                        [
                            $request->get_params(),
                            $callerDependencies
                        ]
                    );
                })
            ]);
        });
    }

    public function getController($functionString)
    {
        return explode('@', $functionString);
    }

    /**
     * Gets token request
     *
     * @return string
     */
    public function getHeaderToken()
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return '';
        }

        $auth = explode("Bearer ", $headers['Authorization']);

        return $auth[1];
    }
}

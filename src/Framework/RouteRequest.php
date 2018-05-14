<?php

namespace Wcom\Jwt\Framework;

use WP_REST_Request;
use Wcom\Jwt\Facades\WordPress;
use Wcom\Jwt\Domain\DomainException;
use Wcom\Jwt\Query\QueryException;
use Wcom\Jwt\App\AppException;
use Exception;

class RouteRequest
{
    public static function handle(callable $controller)
    {
        return function(WP_REST_Request $request) use ($controller) {
            $wp = new WordPress;
            try {
                return call_user_func($controller, $request);
            } catch(DomainException $e) {
                $wp->sendJsonError([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            } catch(QueryException $e) { 
                $wp->sendJsonError([
                    'status' => 500,
                    'message' => $e->getMessage()
                ]);
            } catch(AppException $e) {
                $wp->sendJsonError([
                    'status' => 500,
                    'message' => $e->getMessage()
                ]);
            } catch (Exception $e) {
                error_log($e);
                $wp->sendJsonError([
                    'status' => 500,
                    'message' => 'There has been an internal server error'
                ]);
            }
        };
    }
}

<?php

namespace Imgsvc\Middleware;

/**
 * @class ValidationHandler
 *
 * Inspect a validated request for errors.
 */
class ValidationHandler
{
    public function __invoke($req, $res, $next)
    {
        // @todo Refactor error handling into a \Response\Error class.
        if ($req->getAttribute('has_errors')) {
            $data = [
                "status" => "error",
                "message" => $req->getAttribute('errors'),
            ];
            $body = json_encode($data);

            return $res
                ->withStatus(400)
                ->withHeader("Content-type", "application/json")
                ->write($body);
        } else {
            return $next($req, $res);
        }
    }
}

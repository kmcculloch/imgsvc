<?php

namespace Imgsvc\Middleware;

class ValidationHandler
{
    public function __invoke($req, $res, $next)
    {
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

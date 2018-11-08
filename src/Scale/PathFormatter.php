<?php

namespace Imgsvc\Scale;

class PathFormatter
{
    protected $container;
    protected $width;
    protected $height;
    protected $image;
    protected $origPath;
    protected $derivPath;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke($req, $res, $next)
    {
        $route = $req->getAttribute('route');

        list(
            'width' => $this->width,
            'height' => $this->height,
            'image' => $this->image,
        ) = $route->getArguments();

        list(
            'orig_path' => $this->origPath,
            'deriv_path' => $this->derivPath,
        ) = $this->container->get('settings');

        $req = $req->withAttributes([
            'width' => $this->width,
            'height' => $this->height,
            'origin' => $this->origin(),
            'targetPath' => $this->targetPath(),
            'target' => $this->target(),
        ]);

        return $next($req, $res);
    }

    protected function origin()
    {
        return implode([
            $this->origPath,
            $this->image,
        ]);
    }

    protected function targetPath()
    {
        return implode([
            $this->derivPath,
            'scale/',
            $this->width,
            '/',
            $this->height,
            '/',
        ]);
    }

    protected function target()
    {
        return implode([
            $this->targetPath(),
            $this->image,
        ]);
    }
}

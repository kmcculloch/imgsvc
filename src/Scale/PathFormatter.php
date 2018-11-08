<?php

namespace Imgsvc\Scale;

/**
 * @class PathFormatter
 *
 * Convert route parameters and application settings into image path arguments.
 */
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
        list(
            'width' => $this->width,
            'height' => $this->height,
            'image' => $this->image,
        ) = $req->getAttribute('route')->getArguments();

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

    /**
     * Full path to origin image.
     */
    protected function origin()
    {
        return implode([
            $this->origPath,
            $this->image,
        ]);
    }

    /**
     * Path to storage directory for target image.
     */
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

    /**
     * Full path to target image.
     */
    protected function target()
    {
        return implode([
            $this->targetPath(),
            $this->image,
        ]);
    }
}

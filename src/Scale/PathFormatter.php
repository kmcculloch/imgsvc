<?php

namespace Imgsvc\Scale;

/**
 * @class PathFormatter
 */
class PathFormatter
{
    protected $width;
    protected $height;
    protected $image;
    protected $origPath;
    protected $derivPath;

    public function __construct($args, $settings)
    {
        list(
            'width' => $this->width,
            'height' => $this->height,
            'image' => $this->image,
        ) = $args;

        list(
            'orig_path' => $this->origPath,
            'deriv_path' => $this->derivPath,
        ) = $settings;
    }

    public function origin()
    {
        return implode([
            $this->origPath,
            $this->image,
        ]);
    }

    public function targetPath()
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

    public function target()
    {
        return implode([
            $this->targetPath(),
            $this->image,
        ]);
    }
}

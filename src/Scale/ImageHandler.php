<?php

namespace Imgsvc\Scale;

use Intervention\Image\ImageManager;

class ImageHandler
{
    protected $width;
    protected $height;
    protected $origin;
    protected $targetPath;
    protected $target;

    public function __invoke($req, $res, $next)
    {
        list(
            'width' => $this->width,
            'height' => $this->height,
            'origin' => $this->origin,
            'targetPath' => $this->targetPath,
            'target' => $this->target,
        ) = $req->getAttributes();
            
        if (!$this->targetExists()) {
            $this->makeTarget();
        }

        return $next($req, $res);
    }

    protected function targetExists()
    {
        return file_exists($this->target);
    }

    protected function makeTarget()
    {
        if (!file_exists($this->origin)) {
            throw new \Exception('origin file does not exist');
        }

        if (!file_exists($this->targetPath)) {
            mkdir($this->targetPath, 0777, true);
        }

        $manager = new ImageManager(array('driver' => 'gd'));

        $newImage = $manager
            ->make($this->origin)
            ->resize($this->width, $this->height);

        $newImage->save($this->target);
    }
}

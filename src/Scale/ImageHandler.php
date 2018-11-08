<?php

namespace Imgsvc\Scale;

use Intervention\Image\ImageManager;

/**
 * @class ImageHandler
 *
 * Create target image if it does not exist.
 */
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
            
        // @todo Refactor error handling into a \Response\Error class.
        if (!$this->targetExists()) {
            try {
                $this->makeTarget();
            } catch (\Exception $e) {
                $status = $e->getCode() ?: 500;
                $data = [
                    "status" => "error",
                    "message" => $e->getMessage(),
                ];
                $body = json_encode($data);

                return $res
                    ->withStatus($status)
                    ->withHeader("Content-type", "application/json")
                    ->write($body);
            }
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
            throw new \Exception('Image not found', 400);
        }

        // @todo Configure server so we can use a less permissive folder mode.
        if (!file_exists($this->targetPath)) {
            mkdir($this->targetPath, 0777, true);
        }

        $manager = new ImageManager(array('driver' => 'gd'));

        // @todo Trigger some error conditions to make sure our error response
        // works right and does not expose sensitive information (like filesystem
        // paths).
        $newImage = $manager
            ->make($this->origin)
            ->resize($this->width, $this->height);

        $newImage->save($this->target);
    }
}

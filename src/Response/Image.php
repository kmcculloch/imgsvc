<?php

namespace Imgsvc\Response;

/**
 * @class Image
 *
 * Load target image from filesystem and return it.
 */
class Image
{
    public function __invoke($req, $res)
    {
        $target = $req->getAttribute('target');

        // @todo Wrap in a try/catch in case of errors.
        $image = file_get_contents($target);

        $res->write($image);
        return $res->withHeader('Content-Type', FILEINFO_MIME_TYPE);
    }
}

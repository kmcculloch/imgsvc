<?php

namespace Imgsvc\Response;

class Image
{
    public function __invoke($req, $res)
    {
        $target = $req->getAttribute('target');

        $image = file_get_contents($target);

        $res->write($image);
        return $res->withHeader('Content-Type', FILEINFO_MIME_TYPE);
    }
}

<?php

namespace Imgsvc\Scale;

use Intervention\Image\ImageManager;

/**
 * @class Controller
 */
class Controller
{
    protected $container;

    /**
     * Constructor
     *
     * @param Slim\Container $container
     *    Dependency injection container.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Invoke
     *
     * @param object $request
     * @param object $response
     * @param object $args
     *
     * @return object
     */
    public function __invoke($request, $response, $args)
    {
        // Check to see if our validation middleware found any errors.
        if ($request->getAttribute('has_errors')) {
            print_r($request->getAttribute('errors'));
            return $response->withStatus(400);
        }

        $paths = new PathFormatter($args, $this->container->get('settings'));

        if (file_exists($paths->target())) {
            $image = file_get_contents($paths->target());
            $response->write($image);
            return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
        } elseif (file_exists($paths->origin())) {
            $manager = new ImageManager(array('driver' => 'gd'));

            $newImage = $manager->make($paths->origin())->resize($args['width'], $args['height']);
            if (!file_exists($paths->targetPath())) {
                mkdir($paths->targetPath(), 0777, TRUE);
            }
            $newImage->save($paths->target());

            $image = file_get_contents($paths->target());
            $response->write($image);
            return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
        } else {
            return $response->withStatus(400)->write('original image not found');
        }
    }
}

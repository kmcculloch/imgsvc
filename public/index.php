<?php

use DavidePastore\Slim\Validation\Validation;
use Respect\Validation\Validator as v;
use Slim\App;

require '../vendor/autoload.php';

// Configuration array. In a real app, we'd load these values from
// the local environment instead of hard coding them here.
$config = [
    'settings' => [
        'orig_path' => '/var/www/imgsvc/originals/',
        'deriv_path' => '/var/www/derivatives/',
    ],
];

if (getenv('SLIM_ENV') === 'dev') {
    $config['settings']['displayErrorDetails'] = true;
}

$app = new App($config);
$container = $app->getContainer();

// Set up a piece of middleware to do some basic route parameter validation.
// I'm sure this isn't bulletproof; in a real app we'd wrap validation
// in our own middleware class and write tests.
$dimensionValidator = v::numeric()->positive()->between(1, 2000);
$filenameValidator = v::regex('/^[a-zA-Z0-9]+(.jpg|.png)$/');
$validators = [
    'width' => $dimensionValidator,
    'height' => $dimensionValidator,
    'image' => $filenameValidator,
];
$validationMiddleware = new Validation($validators);

$app->get(
    '/scale/w/{width}/h/{height}/{image}',
    \Imgsvc\Response\Image::class
)
->add(\Imgsvc\Scale\ImageHandler::class)
->add(new \Imgsvc\Scale\PathFormatter($container))
->add(\Imgsvc\Middleware\ValidationHandler::class)
->add($validationMiddleware);

$app->run();

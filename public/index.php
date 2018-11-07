<?php

use DavidePastore\Slim\Validation\Validation;
use Imgsvc\Scale\Controller as ScaleController;
use Respect\Validation\Validator as v;
use Slim\App;

require '../vendor/autoload.php';

// Configuration array. In a real-world app, we'd load these values from
// the local environment instead of hard coding them here.
$config = [
    'settings' => [
        'orig_path' => '/var/www/imgsvc/originals/',
        'deriv_path' => '/var/www/derivatives/',
    ],
];

$app = new App($config);

// 'Hello world'.
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Hello world');
});

// Some basic validation. I'm sure this isn't bulletproof; in a real-world
// development effort we'd wrap validation in our own middleware class and write
// a bunch of tests to guard against errors and attacks.
$scaleValidator = v::numeric()->positive()->between(1, 2000);
$imageValidator = v::regex('/^[a-zA-Z0-9]+(.jpg|.png)$/');
$validators = [
    'width' => $scaleValidator,
    'height' => $scaleValidator,
    'image' => $imageValidator,
];

// Image scale endpoint.
$app->get(
    '/scale/w/{width}/h/{height}/{image}',
    ScaleController::class
)->add(new Validation($validators));;

$app->run();

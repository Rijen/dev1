<?php

use Respect\Validation\Validator as v;

$app->add(new App\Middleware\ValidationErrorMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('\\App\\Validation\\Rules\\');

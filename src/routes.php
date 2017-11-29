<?php

use App\Middleware\AdminAuthMiddleware;
use App\Middleware\AdminGuestMiddleware;
use App\Middleware\RouteNameMiddleware;

// Routes


$app->group('/admin', function () use ($app, $container) {
  $app->group('', function() use($app) {
	$app->get('/login', 'Admin\AuthController:getLogin')->setName('admin.login');
	$app->post('/login', 'Admin\AuthController:postLogin');
  })->add(new AdminGuestMiddleware($container));

  $app->group('', function() use($app, $container) {
	$app->get('/', 'Admin\MainController:index')->setName('admin.main');
	$app->get('/logout', 'Admin\AuthController:getLogout')->setName('admin.logout');

	$app->group('/users', function() use($app) {
	  $app->get('', 'Admin\UserController:index')->setName('admin.users');
	  $app->get('/create', 'Admin\UserController:getCreate')->setName('admin.users.create');
	  $app->get('/{id}/update', 'Admin\UserController:getUpdate');
	  $app->get('/{id}/delete', 'Admin\UserController:getDelete');
	})->add(new RouteNameMiddleware($container));

	$app->group('/users', function() use($app) {
	  $app->post('/create', 'Admin\UserController:postCreate');
	  $app->post('/{id}/update', 'Admin\UserController:postUpdate');
	});

  })->add(new AdminAuthMiddleware($container));
});


$app->get('/', function() use ($app) {
  print_r(App\Models\User::find(1)->email);
});

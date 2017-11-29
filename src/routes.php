<?php

use App\Middleware\AdminAuthMiddleware;
use App\Middleware\AdminGuestMiddleware;
use App\Middleware\RouteNameMiddleware;

// Routes
function crud($base, $controller, $name, &$app) {
  $app->group($base, function() use($app, $controller, $name) {
	$app->get('', $controller . ':index')->setName($name);
	$app->get('/create', $controller . ':getCreate')->setName($name . '.create');
	$app->get('/{id}/update', $controller . ':getUpdate');
	$app->get('/{id}/delete', $controller . ':getDelete');
  })->add(new RouteNameMiddleware($app->getContainer()));

  $app->group($base, function() use($app, $controller) {
	$app->post('/create', $controller . ':postCreate');
	$app->post('/{id}/update', $controller . ':postUpdate');
  });
}

$app->group('/admin', function () use ($app, $container) {
  $app->group('', function() use($app) {
	$app->get('/login', 'Admin\\AuthController:getLogin')->setName('admin.login');
	$app->post('/login', 'Admin\\AuthController:postLogin');
  })->add(new AdminGuestMiddleware($container));


  $app->group('', function() use($app, $container) {
	$app->get('/', 'Admin\\MainController:index')->setName('admin.main');
	$app->get('/logout', 'Admin\\AuthController:getLogout')->setName('admin.logout');

	crud('/users', 'Admin\\UserController', 'admin.users', $app);
	crud('/roles', 'Admin\\RoleController', 'admin.roles', $app);

  })->add(new AdminAuthMiddleware($container));
});


$app->get('/', function() use ($app) {
  print 'Admin\RoleController';
});

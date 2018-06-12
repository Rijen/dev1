<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\RouteNameMiddleware;

// Routes
function crud($base, $controller, $name, &$app)
{
	$app->group($base, function() use($app, $controller, $name)
	{
		$app->get('', $controller . ':index')->setName($name);
		$app->get('/create', $controller . ':getCreate')->setName($name . '.create');
		$app->get('/{id}/update', $controller . ':getUpdate')->setName($name . '.update');
		$app->get('/delete', $controller . ':getDelete')->setName($name . '.delete');
	})->add(new RouteNameMiddleware($app->getContainer()));

	$app->group($base, function() use($app, $controller)
	{
		$app->post('/create', $controller . ':postCreate');
		$app->post('/{id}/update', $controller . ':postUpdate');
	});
}

$app->group('/', function () use ($app, $container)
{
	$app->group('', function() use($app)
	{
		$app->get('login', 'AuthController:getLogin')->setName('login');
		$app->post('login', 'AuthController:postLogin');
	})->add(new GuestMiddleware($container));


	$app->group('', function() use($app)
	{
		$app->get('', 'MainController:index')->setName('main');
		$app->get('logout', 'AuthController:getLogout')->setName('logout');
	})->add(new AuthMiddleware($container));

	$app->group('user_manager', function() use($app)
	{
		$app->get('', 'UserManager\\UserController:index');
		crud('/user', 'UserManager\\UserController', 'user_manager.user', $app);
		$app->get('/user/{id}/groups', 'UserManager\\UserController:getGroups');
		$app->get('/user/{id}/roles', 'UserManager\\UserController:getRoles');

		crud('/group', 'UserManager\\GroupController', 'user_manager.group', $app);
		$app->get('/group/{id}/members', 'UserManager\\GroupController:getMembers');

		$app->get('/components', 'UserManager\\ComponentController:index')
				->setName('user_manager.component')
				->add(new RouteNameMiddleware($app->getContainer()));
	})->add(new AuthMiddleware($container));
});


$app->get('/test', function() use ($app)
{
	print '<pre>';
});

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
	})->add(new RouteNameMiddleware($app->getContainer()));

	$app->group($base, function() use($app, $controller, $name)
	{
		$app->post('/create', $controller . ':postCreate');
		$app->get('/delete', $controller . ':postDelete')->setName($name . '.delete');
		$app->post('/{id}/update', $controller . ':postUpdate');
	});
}

$app->group('/', function () use ($app, $container)
{
	$app->group('', function() use($app)
	{
		$app->get('login', 'Users\\AuthController:getLogin')->setName('login');
		$app->post('login', 'Users\\AuthController:postLogin');
	})->add(new GuestMiddleware($container));


	$app->group('', function() use($app)
	{
		$app->get('', 'MainController:index')->setName('main');
		$app->get('logout', 'Users\\AuthController:getLogout')->setName('logout');

		crud('users', 'Users\\UserController', 'users', $app);
		crud('roles', 'Users\\RoleController', 'roles', $app);
	})->add(new AuthMiddleware($container));

	$app->group('user_manager', function() use($app)
	{
		$app->get('', 'UserManager\\UserController:index');
		crud('/user', 'UserManager\\UserController', 'user_manager.user', $app);
		crud('/group', 'UserManager\\GroupController', 'user_manager.group', $app);
	})->add(new AuthMiddleware($container));
});


$app->get('/test', function() use ($app)
{
	print '<pre>';
	print_r(App\Models\User::find(1)->groups->contains(3));
});

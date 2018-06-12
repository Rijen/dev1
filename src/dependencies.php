<?php

// DIC configuration

$container = $app->getContainer();

$container['Auth'] = function($c)
{
	return new \App\Auth\Auth();
};
$container['flash'] = function($c)
{
	return new \Slim\Flash\Messages;
};
$container['lang'] = function($c) use ($lang)
{

	$def_lang = $c->get('settings')['lang'];
	return $lang[$def_lang];
};

$container['view'] = function ($c) use ($lang)
{
	$view		 = new \Slim\Views\Twig($c->get('settings')['twig']['tpl_path']);
	$def_lang	 = $c->get('settings')['lang'];
// Instantiate and add Slim specific extension
	$basePath	 = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

	$view->getEnvironment()->addGlobal('lang', $lang[$def_lang]);
	$view->getEnvironment()->addGlobal('Auth', [
		'check'	 => $c->Auth->check(),
		'user'	 => $c->Auth->user(),
	]);
	$view->getEnvironment()->addGlobal('flash', $c->flash);

	return $view;
};


$capsule = new \Illuminate\Database\Capsule\Manager();

$capsule->addConnection($container->get('settings')['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function($c) use ($capsule)
{

	return $capsule;
};


$container['validator'] = function($c)
{
	return new \App\Validation\Validator;
};
$container['csrf'] = function($c)
{
	return new \Slim\Csrf\Guard();
};

function register_controllers($list, &$container)
{
	foreach ($list as $name)
	{
		$container[$name] = function($c)use($name)
		{
			$full_name = 'App\\Controllers\\' . $name;
			return new $full_name($c);
		};
	}
}

$register_controllers = [
	'MainController',
	'Users\\AuthController',
	'Users\\RoleController',
	'UserManager\\UserController',
	'UserManager\\GroupController',
];

register_controllers($register_controllers, $container);


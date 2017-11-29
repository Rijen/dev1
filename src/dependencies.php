<?php

// DIC configuration

$container = $app->getContainer();

$container['adminAuth'] = function($c) {
  return new \App\Auth\AdminAuth();
};
$container['flash'] = function($c) {
  return new \Slim\Flash\Messages;
};

$container['view'] = function ($c) {
  $view = new \Slim\Views\Twig($c->get('settings')['twig']['tpl_path']);

// Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

  $view->getEnvironment()->addGlobal('adminAuth', [
	  'check'	 => $c->adminAuth->check(),
	  'user'	 => $c->adminAuth->user(),
  ]);
  $view->getEnvironment()->addGlobal('flash', $c->flash);

  return $view;
};


$capsule = new \Illuminate\Database\Capsule\Manager();

$capsule->addConnection($container->get('settings')['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function($c) use ($capsule) {

  return $capsule;
};


$container['validator'] = function($c) {
  return new \App\Validation\Validator;
};
$container['csrf'] = function($c) {
  return new \Slim\Csrf\Guard();
};

function register_controllers($list, &$container) {
  foreach ($list as $name) {
	$container[$name] = function($c)use($name) {
	  $full_name = 'App\\Controllers\\' . $name;
	  return new $full_name($c);
	};
  }
}

$register_controllers = [
	'Admin\\MainController',
	'Admin\\AuthController',
	'Admin\\UserController',
	'Admin\\RoleController',
];

register_controllers($register_controllers, $container);


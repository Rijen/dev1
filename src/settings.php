<?php

return [
	'settings' => [
		'lang'								 => 'en',
		'displayErrorDetails'				 => true, // set to false in production
		'addContentLengthHeader'			 => false, // Allow the web server to send the content-length header
		'determineRouteBeforeAppMiddleware'	 => true,
// Renderer settings
		'twig'								 => [
			'tpl_path' => __DIR__ . '/../templates/'
		],
		'upload_directory'					 => __DIR__ . '/../public/uploads/',
		'db'								 => [
			'driver'	 => 'mysql',
			'host'		 => 'localhost',
			'database'	 => 'dev1',
			'username'	 => 'www-data',
			'password'	 => '',
			'charset'	 => 'utf8',
			'collation'	 => 'utf8_unicode_ci',
			'prefix'	 => '',
		]
	],
];

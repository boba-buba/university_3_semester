<?php

/*
 * This is an example of a simple application, that administers a list of notes (short strings).
 * It does not utilize any framework or 3rd party library, but it demonstrate some major
 * principles of modern cgi-like web applications.
 * 
 * Please note that some parts are simplified as the main objective is to demonstrate principles
 * not provide commercial, deployment-ready solution.
 */


// Loading can be done better with autoload; however, in this example we just load everything...
foreach (['lib', 'model', 'controllers'] as $dir) {
	foreach (glob("$dir/*.php") as $include) {
		require_once(__DIR__ . "/$include");
	}
}


try {
	// Initialize and configure the container
	$config = require(__DIR__ . '/config/config.ini.php');
	$container = new Container();
	$container->init($config);

	// Get the router and dispatch the request
	$router = $container->getByName('Router');
	$router->dispatch();
}
catch (Exception $e) {
	// Error logging is very important. This should be done much better...
	die("Uncaught Exception: " . $e->getMessage());
}

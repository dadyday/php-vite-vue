<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\RouteList;


final class RouterFactory {
	public static function createRouter(): RouteList {
		$router = new RouteList;
		$router->addRoute('/', 'App:main');
		$router->addRoute('/login', 'App:login');
		$router->addRoute('/bar?<n>', 'App:bar');
		$router->addRoute('/<view>', 'App:default');
		//$router->addRoute('/entities?[page=<page>]]', 'Main:entities');
		return $router;
	}
}

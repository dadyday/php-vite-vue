<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\RouteList;


final class RouterFactory {
	public static function createRouter(): RouteList {
		$router = new RouteList;
		$router->addRoute('/entities?[page=<page>]]', 'Main:entities');
		$router->addRoute('/<action>', 'Main:default');
		return $router;
	}
}

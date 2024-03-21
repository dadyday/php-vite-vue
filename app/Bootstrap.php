<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;


class Bootstrap {
	public static function boot(): Configurator {
		$oConfig = new Configurator;

		$rootDir = dirname(__DIR__);
		$oConfig->addStaticParameters(['rootDir' => $rootDir]);

		//$oConfig->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$oConfig->enableTracy($rootDir . '/temp/log');

		$oConfig->setTempDirectory($rootDir . '/temp');

		$oConfig->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$envFile = $rootDir.'/.env';
		$aEnv = parse_ini_file($envFile);
		$oConfig->addStaticParameters($aEnv);

		$oConfig->addConfig($rootDir . '/app/cfg.neon');

		return $oConfig;
	}
}

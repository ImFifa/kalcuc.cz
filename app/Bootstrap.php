<?php declare(strict_types = 1);

namespace App;

use Nette\Configurator;
use OriNette\DI\Boot\Environment;
use OriNette\DI\Boot\FileDebugCookieStorage;
use function define;
use function dirname;

class Bootstrap
{

	public static function boot(): Configurator
	{
		define('WWW', dirname(__DIR__) . '/www');

		$configurator = new Configurator();
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$cookieStorage = new FileDebugCookieStorage(__DIR__ . '/config/debug.json');
		$configurator->addServices([
			'orisai.di.cookie.storage' => $cookieStorage,
		]);

		$configurator->setDebugMode(
			Environment::isEnvDebug() ||
			Environment::isCookieDebug($cookieStorage),
		);
		$configurator->enableDebugger(__DIR__ . '/../log');

		$configurator->addConfig(__DIR__ . '/../vendor/owly-cms/core-module/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../vendor/owly-cms/box-module/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../vendor/owly-cms/file-module/config/config.neon');

		$configurator->addConfig(__DIR__ . '/config/config.neon');
		$configurator->addConfig(__DIR__ . '/config/server/local.neon');
		$configurator->addParameters([
			'rootDir' => dirname(__DIR__),
			'wwwDir' => dirname(__DIR__) . '/www',
		]);

		return $configurator;
	}

}

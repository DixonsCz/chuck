<?php

use Nette\Diagnostics\Debugger;

define('APP_DIR', __DIR__ . '/../app');

require __DIR__ . '/../vendor/autoload.php';
\Nette\Diagnostics\Debugger::enable(Debugger::DEVELOPMENT);

// Configure application
$configurator = new \Nette\Configurator();
$configurator->setDebugMode(true);
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->enableDebugger(__DIR__ . '/../log');
$configurator->createRobotLoader()
->addDirectory(__DIR__ . '/../app')
->addDirectory(__DIR__ . '/../components')
->register();

// basic environment resolution
$environment = null;

// jenkins server runs on nginx
if (false !== strstr($_SERVER["SERVER_SOFTWARE"], "nginx")) {
$environment = "jenkins";
$configurator->setDebugMode(false);
\Nette\Diagnostics\Debugger::enable(Debugger::PRODUCTION);
}

$configurator->addConfig(__DIR__ . '/../app/config/config.neon', $environment);

$container = $configurator->createContainer();
$container->application->catchExceptions = false;

return $container;

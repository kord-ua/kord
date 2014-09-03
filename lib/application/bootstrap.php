<?php

defined('DOCROOT') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Load File System handler
 */
$files = [
    '/vendor/KORD/FileSystem/FileSystemInterface',
    '/vendor/KORD/FileSystem/Cascade'
];

foreach ($files as $file) {
    if (is_file('lib/application' . $file . EXT)) {
        require DOCROOT . 'lib/application' . $file . EXT;
    } else {
        require DOCROOT . 'lib/system' . $file . EXT;
    }
}

$paths = [
    DOCROOT . 'lib/application/', // Application
    // DOCROOT . 'lib/database/',       // Database module
    DOCROOT . 'lib/system/', // System
];

$filesystem = (new \KORD\Filesystem\Cascade())->setIncludePaths($paths);

/**
 * Load PSR-0 autoloader
 */
require $filesystem->findFile('vendor/KORD/Autoload', 'Psr0');

$psr0 = new \KORD\Autoload\Psr0($filesystem);
spl_autoload_register([$psr0, 'autoLoad']);

/**
 * Load PSR-4 autoloader
 */
$psr4 = new \KORD\Autoload\Psr4;
$psr4->register();

/**
 * Enable the autoloader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// Start an output buffer
ob_start();

/**
 * Init Aura DI and Aura Router
 */
$psr4->addNamespace('Aura\\Di', $filesystem->findDir('vendor/Aura/Di/src'));
$psr4->addNamespace('Aura\\Router', $filesystem->findDir('vendor/Aura/Router/src'));

$app = new \Aura\Di\Container(new \Aura\Di\Factory());

$router_factory = new \Aura\Router\RouterFactory();
$router = $router_factory->newInstance();

// init routes
require_once $filesystem->findFile('bootstrap', 'routes');

// init Di
require_once $filesystem->findFile('bootstrap', 'di');

// Enable KORD exception handling, adds stack traces and error source.
set_exception_handler([$app->get('exception'), 'handler']);

// Enable KORD error handling, converts all PHP errors to exceptions.
set_error_handler([$app->get('error'), 'handler']);

// Enable the KORD shutdown handler, which catches E_FATAL errors.
register_shutdown_function([$app->get('shutdown'), 'handler']);

/**
 * Init modules
 */
foreach ($paths as $appr) {
    if (is_file($appr . 'init' . EXT)) {
        require_once $appr . 'init' . EXT;
    }
}

echo $app->get('request_factory')->newInstance()->execute()->sendHeaders()->getBody();

echo $app->get('view_factory')->newInstance()->set(['filesystem' => $filesystem, 'profiler' => $app->get('profiler')])->render('profiler/stats');

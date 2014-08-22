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

// Process request
$app->params['KORD\Mvc\RequestFactory'] = [
    'router' => $app->lazyGet('router'),
    'closure' => $app->newFactory('KORD\Mvc\Request')
];

$app->params['KORD\Mvc\Controller'] = [
    'request_factory' => $app->lazyGet('request_factory'),
    'response' => $app->lazyGet('response')
];

$app->params['KORD\Mvc\View'] = [
    'filesystem' => $filesystem
];

$app->setter['KORD\Mvc\Controller']['setArr'] = $app->lazyGet('arr');

// helpers
$app->set('arr', $app->lazyNew('KORD\Helper\Arr'));

// utilities
$app->set('profiler', $app->newInstance('KORD\Utils\Profiler'));

// mvc
$app->set('request_factory', $app->lazyNew('KORD\Mvc\RequestFactory'));

$app->set('controller', $app->lazyNew('KORD\Mvc\Controller'));

$app->set('response', $app->lazyNew('KORD\Mvc\Response'));

$app->set('router', $router);

$app->set('view', $app->lazyNew('KORD\Mvc\View'));

/**
 * Init modules
 */
foreach ($paths as $appr) {
    if (is_file($appr . 'init' . EXT)) {
        require_once $appr . 'init' . EXT;
    }
}

echo $app->get('request_factory')->newInstance()->execute()->getBody();

echo $app->get('view')->set(['filesystem' => $filesystem, 'profiler' => $app->get('profiler')])->render('profiler/stats');

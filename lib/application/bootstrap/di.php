<?php

defined('DOCROOT') or die('No direct script access.');

// -- Security parameters (SET NEW FOR EVERY NEW APPLICATION)  -----------------

# cookie salt
$cookie_salt = 'thisisatestsalt';

// -- Setup HELPERS  -----------------------------------------------------------
# Arr
$app->set('arr', $app->lazyNew('KORD\Helper\Arr'));

# Cookie
$app->params['KORD\Helper\Cookie'] = [
    'options' => [
        'salt' => $cookie_salt
    ]
];
$app->set('cookie', $app->lazyNew('KORD\Helper\Cookie'));

# UTF8
$app->params['KORD\Helper\UTF8'] = [
    'filesystem' => $filesystem
];
$app->set('utf8', $app->lazyNew('KORD\Helper\UTF8'));

// -- Setup CONFIG  ------------------------------------------------------------
$app->params['KORD\Config\Repository'] = [
    'group_closure' => $app->newFactory('KORD\Config\Group'),
    'arr' => $app->lazyGet('arr')
];

$app->params['KORD\Config\File\Reader'] = [
    'filesystem' => $filesystem,
    'arr' => $app->lazyGet('arr')
];

$app->set('config', $app->lazyNew('KORD\Config\Repository'));
$app->set('config_reader', $app->lazyNew('KORD\Config\File\Reader'));

// attach config reader
$app->get('config')->attach($app->get('config_reader'));

// -- Setup Handlers  ----------------------------------------------------------
/**
 * Exception
 */
$app->params['KORD\Error\ExceptionHandler'] = [
    'response'      => $app->lazyNew('KORD\Mvc\Response'),
    'view_factory'  => $app->lazyGet('view_factory'),
    'debug'         => $app->lazyGet('debug'),
    'config'        => (array) $app->get('config')->load('core')
];
$app->set('exception', $app->lazyNew('KORD\Error\ExceptionHandler'));

/**
 * Debug
 */
$app->params['KORD\Error\Debug'] = [
    'charset' => $app->get('config')->load('core')->get('charset'),
    'utf8' => $app->lazyGet('utf8'),
];
$app->set('debug', $app->lazyNew('KORD\Error\Debug'));

// -- Setup MVC  ---------------------------------------------------------------
/**
 * Request Factory
 */
$app->params['KORD\Mvc\RequestFactory'] = [
    'router'    => $app->lazyGet('router'),
    'closure'   => $app->newFactory('KORD\Mvc\Request')
];
$app->set('request_factory', $app->lazyNew('KORD\Mvc\RequestFactory'));

/**
 * Controller
 */
$app->params['KORD\Mvc\Controller'] = [
    'request_factory'   => $app->lazyGet('request_factory'),
    'response'          => $app->lazyNew('KORD\Mvc\Response')
];
$app->setter['KORD\Mvc\Controller']['setArr'] = $app->lazyGet('arr');
$app->setter['KORD\Mvc\Controller']['setCookie'] = $app->lazyGet('cookie');
$app->setter['KORD\Mvc\Controller']['setUtf8'] = $app->lazyGet('utf8');
$app->setter['KORD\Mvc\Controller']['setViewFactory'] = $app->lazyGet('view_factory');
$app->setter['KORD\Mvc\Controller']['setViewGlobal'] = $app->lazyGet('view_global');

$app->set('controller', $app->lazyNew('KORD\Mvc\Controller'));

/**
 * Response
 */
$app->params['KORD\Mvc\Response'] = [
    'header' => $app->lazyNew('KORD\Mvc\Header'),
    'cookie' => $app->lazyGet('cookie'),
    'config' => (array) $app->get('config')->load('core')
];

/**
 * View Factory and View Global
 */
$app->params['KORD\Mvc\ViewFactory'] = [
    'closure' => $app->newFactory('KORD\Mvc\View')
];

$app->params['KORD\Mvc\View'] = [
    'filesystem'    => $filesystem,
    'view_global'   => $app->lazyGet('view_global')
];

$app->set('view_factory', $app->lazyNew('KORD\Mvc\ViewFactory'));
$app->set('view_global', $app->lazyNew('KORD\Mvc\View', ['view_global' => null]));

/**
 * Router
 */
$app->set('router', $router);

// -- Setup UTILITIES  ---------------------------------------------------------
/**
 * Profiler
 */
$app->set('profiler', $app->newInstance('KORD\Utils\Profiler'));

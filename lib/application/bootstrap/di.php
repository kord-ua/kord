<?php

defined('DOCROOT') or die('No direct script access.');

// -- Setup DI container  ------------------------------------------------------

$app->params['KORD\Mvc\RequestFactory'] = [
    'router' => $app->lazyGet('router'),
    'closure' => $app->newFactory('KORD\Mvc\Request')
];

$app->params['KORD\Mvc\Controller'] = [
    'request_factory' => $app->lazyGet('request_factory'),
    'response' => $app->lazyNew('KORD\Mvc\Response')
];

$app->params['KORD\Mvc\Response'] = [
    'header' => $app->lazyNew('KORD\Mvc\Header'),
    'cookie' => $app->lazyGet('cookie')
];

$app->params['KORD\Mvc\ViewFactory'] = [
    'closure' => $app->newFactory('KORD\Mvc\View')
];

$app->params['KORD\Mvc\View'] = [
    'filesystem' => $filesystem,
    'view_global' => $app->lazyGet('view_global')
];

$app->setter['KORD\Mvc\Controller']['setArr'] = $app->lazyGet('arr');
$app->setter['KORD\Mvc\Controller']['setCookie'] = $app->lazyGet('cookie');
$app->setter['KORD\Mvc\Controller']['setViewFactory'] = $app->lazyGet('view_factory');
$app->setter['KORD\Mvc\Controller']['setViewGlobal'] = $app->lazyGet('view_global');

/**
 * HELPERS
 */
# Arr
$app->set('arr', $app->lazyNew('KORD\Helper\Arr'));
# Cookie
$app->params['KORD\Helper\Cookie'] = [
    'options' => [
        'salt' => 'thisisatestsalt'
    ]
];
$app->set('cookie', $app->lazyNew('KORD\Helper\Cookie'));

// utilities
$app->set('profiler', $app->newInstance('KORD\Utils\Profiler'));

// mvc
$app->set('request_factory', $app->lazyNew('KORD\Mvc\RequestFactory'));

$app->set('controller', $app->lazyNew('KORD\Mvc\Controller'));

$app->set('router', $router);

$app->set('view_factory', $app->lazyNew('KORD\Mvc\ViewFactory'));
$app->set('view_global', $app->lazyNew('KORD\Mvc\View', ['view_global' => null]));
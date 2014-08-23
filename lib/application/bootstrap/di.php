<?php

defined('DOCROOT') or die('No direct script access.');

// -- Setup DI container  ------------------------------------------------------

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
<?php

defined('DOCROOT') or die('No direct script access.');

// -- Security parameters (SET NEW FOR EVERY NEW APPLICATION)  -----------------

# cookie salt
$cookie_salt = 'thisisatestsalt';

# encrypt key
$encrypt_key = 'thisisatestencryptkey';

# hmac hash key
$hash_key = 'thisisatesthashkey';

// -- Setup HELPERS  -----------------------------------------------------------
/**
 * Arr
 */
$app->set('arr', $app->lazyNew('KORD\Helper\Arr'));

/**
 * Cookie
 */
$app->params['KORD\Helper\Cookie'] = [
    'options' => [
        'salt' => $cookie_salt
    ]
];
$app->set('cookie', $app->lazyNew('KORD\Helper\Cookie'));

/**
 * Date
 */
$app->params['KORD\I18n\Date\Format'] = [
    'i18n' => $app->lazyGet('i18n')
];
$app->params['KORD\Helper\Date'] = [
    'date_format_closure' => $app->newFactory('KORD\I18n\Date\Format'),
    'i18n' => $app->lazyGet('i18n')
];
$app->set('date', $app->lazyNew('KORD\Helper\Date'));

/**
 * Random
 */
$app->set('random', $app->lazyNew('KORD\Helper\Random'));

/**
 * UTF8
 */
$app->params['KORD\Helper\UTF8'] = [
    'filesystem' => $filesystem
];
$app->set('utf8', $app->lazyNew('KORD\Helper\UTF8'));

// -- Setup CONFIG  ------------------------------------------------------------
/**
 * Repository
 */
$app->params['KORD\Config\Repository'] = [
    'group_closure' => $app->newFactory('KORD\Config\Group'),
    'arr' => $app->lazyGet('arr'),
    'sources' => [
        $app->lazyGet('config_reader')
    ]
];
$app->set('config', $app->lazyNew('KORD\Config\Repository'));

/**
 * Reader
 */
$app->params['KORD\Config\File\Reader'] = [
    'filesystem' => $filesystem,
    'arr' => $app->lazyGet('arr')
];
$app->set('config_reader', $app->lazyNew('KORD\Config\File\Reader'));

// -- Setup Handlers  ----------------------------------------------------------
/**
 * Exception
 */
$app->params['KORD\Error\ExceptionHandler'] = [
    'response_factory'  => $app->lazyGet('response_factory'),
    'view_factory'      => $app->lazyGet('view_factory'),
    'debug'             => $app->lazyGet('debug'),
    'config'            => (array) $app->get('config')->load('core')
];
$app->set('exception', $app->lazyNew('KORD\Error\ExceptionHandler'));

/**
 * Error
 */
$app->set('error', $app->lazyNew('KORD\Error\ErrorHandler'));

/**
 * Shutdown
 */
$app->params['KORD\Error\ShutdownHandler'] = [
    'exception_handler' => $app->lazyGet('exception'),
];
$app->set('shutdown', $app->lazyNew('KORD\Error\ShutdownHandler'));

/**
 * Debug
 */
$app->params['KORD\Error\Debug'] = [
    'charset' => $app->get('config')->load('core')->get('charset'),
    'utf8' => $app->lazyGet('utf8'),
];
$app->set('debug', $app->lazyNew('KORD\Error\Debug'));

// -- Setup Crypt  -------------------------------------------------------------
/**
 * Hash
 */
$app->params['KORD\Crypt\Hash'] = [
    'key' => $hash_key
];
$app->set('hash', $app->lazyNew('KORD\Crypt\Hash'));

/**
 * Encrypt
 */
$app->params['KORD\Crypt\Encrypt'] = [
    'key' => $encrypt_key
];
$app->set('encrypt', $app->lazyNew('KORD\Crypt\Encrypt'));

/**
 * PasswordHash
 */
$app->set('password_hash', $app->lazyNew('KORD\Crypt\PasswordHash\Pbkdf2'));

// -- Setup Session  -----------------------------------------------------------
$app->params['KORD\Session\Native'] = [
    'cookie' => $app->lazyGet('cookie'),
    'encrypt' => $app->lazyGet('encrypt')
];
$app->set('session', $app->lazyNew('KORD\Session\Native'));

// -- Setup I18n  --------------------------------------------------------------
/**
 * Repository
 */
$app->params['KORD\I18n\Repository'] = [
    'readers' => [
        $app->lazyGet('i18n_reader')
    ],
    'lang' => 'ru'
];
$app->set('i18n', $app->lazyNew('KORD\I18n\Repository'));

/**
 * Reader
 */
$app->params['KORD\I18n\Reader\File'] = [
    'arr' => $app->lazyGet('arr'),
    'filesystem' => $filesystem
];
$app->set('i18n_reader', $app->lazyNew('KORD\I18n\Reader\File'));

// -- Setup MVC  ---------------------------------------------------------------
/**
 * Request Factory
 */
$app->params['KORD\Mvc\RequestFactory'] = [
    'router'    => $app->lazyGet('router'),
    'closure'   => $app->newFactory('KORD\Mvc\Request'),
    'clients' => [
        'internal' => $app->newFactory('KORD\Mvc\Request\Client\Internal'),
        //'curl' => $app->newFactory('KORD\Mvc\Request\Client\Curl')
    ]
];
$app->set('request_factory', $app->lazyNew('KORD\Mvc\RequestFactory'));

/**
 * Request client
 */
$app->params['KORD\Mvc\Request\ClientAbstract'] = [
    'response_factory' => $app->lazyGet('response_factory')
];
$app->setter['KORD\Mvc\Request\ClientAbstract']['setProfiler'] = $app->lazyGet('profiler');

/**
 * Controller
 */
$app->setter['KORD\Mvc\Controller'] = [
    'setArr' => $app->lazyGet('arr'),
    'setConfig' => $app->lazyGet('config'),
    'setCookie' => $app->lazyGet('cookie'),
    'setDate' => $app->lazyGet('date'),
    'setEncrypt' => $app->lazyGet('encrypt'),
    'setHash' => $app->lazyGet('hash'),
    'setI18n' => $app->lazyGet('i18n'),
    'setPasswordHash' => $app->lazyGet('password_hash'),
    'setRandom' => $app->lazyGet('random'),
    'setRequestFactory' => $app->lazyGet('request_factory'),
    'setSession' => $app->lazyGet('session'),
    'setUtf8' => $app->lazyGet('utf8'),
    'setViewFactory' => $app->lazyGet('view_factory'),
    'setViewGlobal' => $app->lazyGet('view_global')
];
$app->set('controller', $app->lazyNew('KORD\Mvc\Controller'));

/**
 * Response factory
 */
$app->params['KORD\Mvc\ResponseFactory'] = [
    'closure'   => $app->newFactory('KORD\Mvc\Response')
];
$app->set('response_factory', $app->lazyNew('KORD\Mvc\ResponseFactory'));

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

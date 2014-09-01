<?php

defined('DOCROOT') or die('No direct script access.');

// -- Setup routes -------------------------------------------------------------

$router->add('home', '')
        ->addValues([
            'controller' => $app->newFactory('Application\Controller\WelcomeController'),
            'action' => 'index',
        ]);

$router->add('test', 'test')
        ->addValues([
            'controller' => $app->newFactory('Application\Controller\SubController'),
            'action' => 'index',
        ]);
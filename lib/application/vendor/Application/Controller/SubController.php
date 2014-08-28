<?php

namespace Application\Controller;

class SubController extends \KORD\Mvc\Controller
{
    
    public function indexAction()
    {
        $this->view_global->set('test', 'works');
        $this->response->setHeader('Content-Type', 'text/xml');
        $this->response->setBody('This is sub-response');
    }

}

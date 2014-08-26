<?php

namespace Application\Controller;

class SubController extends \KORD\Mvc\Controller
{
    
    public function indexAction()
    {
        $this->response->setHeader('Content-Type', 'text/xml');
        $this->response->setBody('This is sub-response');
    }

}

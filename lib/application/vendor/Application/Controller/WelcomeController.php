<?php

namespace Application\Controller;

class WelcomeController extends \KORD\Mvc\Controller
{
    
    public function indexAction()
    {
        $testarr = ['foo' => 'barbaz'];
        
        // test cookie
        $this->cookie->set('test', 'coookie');
        echo $this->cookie->get('test');
        
        // test sub-request
        echo $this->request_factory->newInstance('/test')->execute()->getBody();
        
        // test global view
        echo $this->view_global->test;
        
        $this->response->setHeader('Content-Type', 'text/html')->setStatus(404);
        $this->response->setBody($this->arr->get($testarr, 'foo'));
    }

}

<?php

namespace Application\Controller;

class WelcomeController extends \KORD\Mvc\Controller
{
    
    public function indexAction()
    {
        //throw new \Exception('test', E_ERROR);
        
        // test session
        $this->session->set('sessiontest', 'sessiontestvalue');
        echo $this->session->get('sessiontest');
        
        //throw new \Exception('test', 301);
        $testarr = ['foo' => 'barbaz'];
        
        // test cookie
        $this->cookie->set('test', 'coookie');
        echo $this->cookie->get('test');
        
        echo $this->i18n->translate(':count files', 10, [':count' => 10]);
        
        // test sub-request
        echo $this->request_factory->newInstance('/test')->execute()->getBody();
        
        // test global view
        echo $this->view_global->test;
        
        $this->response->setHeader('Content-Type', 'text/html')->setStatus(404);
        $this->response->setBody($this->arr->get($testarr, 'foo'));
    }

}

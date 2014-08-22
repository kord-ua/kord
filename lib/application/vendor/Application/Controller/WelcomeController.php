<?php

namespace Application\Controller;

class WelcomeController extends \KORD\Mvc\Controller
{
    
    public function indexAction()
    {
        $testarr = ['foo' => 'barbaz'];
        
        echo $this->request_factory->newInstance('/test')->execute()->getBody();
        
        $this->response->setBody($this->arr->get($testarr, 'foo'));
    }

}

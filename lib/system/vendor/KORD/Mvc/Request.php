<?php

namespace KORD\Mvc;

class Request
{

    /**
     * @var Aura\Router\Router 
     */
    protected $router;

    /**
     * @var string 
     */
    protected $uri;

    /**
     * @var string 
     */
    protected $controller;

    /**
     * @var string 
     */
    protected $action;

    public function __construct()
    {
        //
    }
    
    public function setController($controller)
    {
       $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }
    
    public function setAction($action)
    {
       $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function execute()
    {
        $controller = $this->getController();
        return $controller($this)->execute();
    }

}

<?php

namespace KORD\Mvc;

class Request implements RequestInterface
{

    /**
     * @var string 
     */
    protected $uri;

    /**
     * @var object 
     */
    protected $controller;

    /**
     * @var string 
     */
    protected $action;

    /**
     * Construct new request
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Set request controller
     * 
     * @param object $controller
     */
    public function setController($controller)
    {
       $this->controller = $controller;
    }

    /**
     * Get request controller
     * 
     * @return object
     */
    public function getController()
    {
        return $this->controller;
    }
    
    /**
     * Set request controller action
     * 
     * @param string $action
     */
    public function setAction($action)
    {
       $this->action = $action;
    }

    /**
     * Get request controller action
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Execute request
     * 
     * @return \KORD\Mvc\ResponseInterface
     */
    public function execute()
    {
        $controller = $this->getController();
        return $controller($this)->execute();
    }

}

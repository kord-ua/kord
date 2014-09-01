<?php

namespace KORD\Mvc;

use KORD\Mvc\Request\ClientInterface;

class Request implements RequestInterface
{
    
    /**
     *
     * @var \KORD\Mvc\Request\ClientInterface
     */
    protected $client;

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
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
     * Set request uri
     * 
     * @param string $uri
     */
    public function setUri($uri)
    {
       $this->uri = $uri;
    }

    /**
     * Get request uri
     * 
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Execute request
     * 
     * @return \KORD\Mvc\ResponseInterface
     */
    public function execute()
    {
        return $this->client->execute($this);
    }

}

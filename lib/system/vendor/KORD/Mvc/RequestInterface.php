<?php

namespace KORD\Mvc;

interface RequestInterface
{

    /**
     * Set request controller
     * 
     * @param object $controller
     */
    public function setController($controller);

    /**
     * Get request controller
     * 
     * @return object
     */
    public function getController();
    
    /**
     * Set request controller action
     * 
     * @param string $action
     */
    public function setAction($action);

    /**
     * Get request controller action
     * 
     * @return string
     */
    public function getAction();
    
    /**
     * Set request uri
     * 
     * @param string $uri
     */
    public function setUri($uri);

    /**
     * Get request uri
     * 
     * @return string
     */
    public function getUri();

    /**
     * Execute request
     * 
     * @return \KORD\Mvc\ResponseInterface
     */
    public function execute();

}

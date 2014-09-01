<?php

namespace KORD\Mvc;

class ResponseFactory implements ResponseFactoryInterface
{
    
    /**
     * @var object 
     */
    protected $closure;

    /**
     * Construct new response factory
     * 
     * @param object $closure
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    /**
     * Create new response instance
     * 
     * @return \KORD\Mvc\ResponseInterface
     */
    public function newInstance()
    {
        $closure = $this->closure;
        $response = $closure();
        
        return $response;
    }

}

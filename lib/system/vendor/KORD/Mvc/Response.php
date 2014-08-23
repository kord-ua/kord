<?php

namespace KORD\Mvc;

class Response implements ResponseInterface
{
    
    /**
     * @var string 
     */
    protected $body;

    /**
     * Construct new response
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Get response body
     * 
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Set response body
     * 
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = (string) $body;
    }

}

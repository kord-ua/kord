<?php

namespace KORD\Mvc;

class Response
{
    
    /**
     * @var string 
     */
    protected $body;

    public function __construct()
    {
        //
    }
    
    public function getBody()
    {
        return $this->body;
    }
    
    public function setBody($body)
    {
        $this->body = (string) $body;
    }

}

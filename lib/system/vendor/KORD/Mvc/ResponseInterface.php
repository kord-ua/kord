<?php

namespace KORD\Mvc;

interface ResponseInterface
{
    
    /**
     * Get response body
     * 
     * @return string
     */
    public function getBody();
    
    /**
     * Set response body
     * 
     * @param string $body
     */
    public function setBody($body);

}

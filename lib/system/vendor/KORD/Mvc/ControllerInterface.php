<?php

namespace KORD\Mvc;

interface ControllerInterface
{
    
    /**
     * Execute request, return response
     * 
     * @return KORD\Mvc\ResponseInterface 
     */
    public function execute();
    
    /**
     * Automatically executed before the controller action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before();

    /**
     * Automatically executed after the controller action. Can be used to apply
     * transformation to the response, add extra output, and execute
     * other custom code.
     *
     * @return  void
     */
    public function after();

}

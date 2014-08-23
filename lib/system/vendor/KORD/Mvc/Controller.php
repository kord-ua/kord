<?php

namespace KORD\Mvc;

class Controller implements ControllerInterface
{
    
    /**
     * @var KORD\Mvc\RequestFactoryInterface
     */
    protected $request_factory;
    
    /**
     * @var KORD\Mvc\RequestInterface 
     */
    protected $request;
    
    /**
     * @var KORD\Mvc\ResponseInterface 
     */
    protected $response;
    
    /**
     * @var KORD\Helper\ArrInterface
     */
    protected $arr;

    public function __construct(RequestInterface $request, RequestFactoryInterface $request_factory, ResponseInterface $response)
    {
        $this->request = $request;
        $this->request_factory = $request_factory;
        $this->response = $response;
    }
    
    /**
     * Test helper injection
     * 
     * @param \KORD\Helper\ArrInterface $arr
     */
    public function setArr(\KORD\Helper\ArrInterface $arr)
    {
        $this->arr = $arr;
    }
    
    /**
     * Execute request, return response
     * 
     * @return KORD\Mvc\ResponseInterface 
     */
    public function execute()
    {
        $this->before();
        
        $action = $this->request->getAction() . 'Action';
        
        $this->{$action}();
        
        $this->after();
        
        return $this->response;
    }
    
    /**
     * Automatically executed before the controller action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        // Nothing by default
    }

    /**
     * Automatically executed after the controller action. Can be used to apply
     * transformation to the response, add extra output, and execute
     * other custom code.
     *
     * @return  void
     */
    public function after()
    {
        // Nothing by default
    }

}

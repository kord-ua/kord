<?php

namespace KORD\Mvc\Request;

use KORD\Mvc\RequestInterface;
use KORD\Mvc\ResponseFactoryInterface;
use KORD\Mvc\ResponseInterface;
use KORD\Utils\ProfilerInterface;

abstract class ClientAbstract implements ClientInterface
{
    
    /**
     * @var \KORD\Mvc\ResponseFactoryInterface 
     */
    protected $response_factory;
    
    /**
     * @var \KORD\Utils\ProfilerInterface 
     */
    protected $profiler;

    /**
     * Construct new request client
     * 
     * @param array $params
     * @param \KORD\Mvc\ResponseFactoryInterface $response_factory
     */
    public function __construct(array $params = [], ResponseFactoryInterface $response_factory)
    {
        $this->response_factory = $response_factory;
    }
    
    /**
     * Assign profiler
     * 
     * @param \KORD\Utils\ProfilerInterface $profiler
     */
    public function setProfiler(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * Processes the request, executing the controller action that handles this
     * request.
     *
     * By default, the output from the controller is captured and returned, and
     * no headers are sent.
     *
     *     $request->execute();
     *
     * @param   \KORD\Mvc\RequestInterface   $request
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function execute(RequestInterface $request)
    {
        // Execute the request and pass the currently used protocol
        $response = $this->response_factory->newInstance();
        
        $response = $this->executeRequest($request, $response);
        
        return $response;
    }
    
    /**
     * Processes the request passed to it and returns the response
     *
     * This method must be implemented by all clients.
     *
     * @param   \KORD\Mvc\RequestInterface  $request   request to execute by client
     * @param   \KORD\Mvc\ResponseInterface $response
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function executeRequest(RequestInterface $request, ResponseInterface $response)
    {
        throw new \Exception("Request Client should implement it's own executeRequest method");
    }
    
}

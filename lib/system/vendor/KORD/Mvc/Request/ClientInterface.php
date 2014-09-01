<?php

namespace KORD\Mvc\Request;

use KORD\Mvc\RequestInterface;
use KORD\Mvc\ResponseInterface;

interface ClientInterface
{

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
    public function execute(RequestInterface $request);
    
    /**
     * Processes the request passed to it and returns the response
     *
     * This method must be implemented by all clients.
     *
     * @param   \KORD\Mvc\RequestInterface  $request   request to execute by client
     * @param   \KORD\Mvc\ResponseInterface $response
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function executeRequest(RequestInterface $request, ResponseInterface $response);
}

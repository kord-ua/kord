<?php

namespace KORD\Mvc\Request\Client;

use KORD\Mvc\RequestInterface;
use KORD\Mvc\ResponseInterface;
use KORD\Mvc\Request\ClientAbstract;
use KORD\Mvc\Request\ClientInterface;

class Internal extends ClientAbstract implements ClientInterface
{

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
        $controller = $request->getController();
        
        if (isset($this->profiler)) {
            // Set the benchmark name
            $benchmark = '"' . $request->getUri() . '"';
            
            // Start benchmarking
            $benchmark = $this->profiler->start('Request', $benchmark);
        }
        
        $response = $controller($request, $response)->execute();
        
        if (isset($benchmark)) {
            // Stop the benchmark
            $this->profiler->stop($benchmark);
        }
        
        return $response;
    }

}

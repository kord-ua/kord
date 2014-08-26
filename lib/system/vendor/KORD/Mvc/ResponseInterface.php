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
     * @return KORD\Mvc\ResponseInterface
     */
    public function setBody($body);
    
    /**
     * Set HTTP status for this response
     * 
     *      // Set the HTTP status to 404 Not Found
     *      $response->setStatus(404);
     * 
     * @param integer $status  Status to set to this response
     * @return KORD\Mvc\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function setStatus($status);

    /**
     * Get HTTP status of this response
     * 
     * @return integer
     */
    public function getStatus();

    /**
     * Set response header
     * 
     *      // Set a header
     *      $response->setHeader('Content-Type', 'text/html');
     * 
     * @param string $key
     * @param string $value
     * @return KORD\Mvc\ResponseInterface
     */
    public function setHeader($key, $value);
    
    /**
     * Set multiple response headers
     * 
     *      // Set multiple headers
     *      $response->setHeaders(['Content-Type' => 'text/html', 'Cache-Control' => 'no-cache']);
     * 
     * @param array $array
     */
    public function setHeaders(array $array);
    
    /**
     * Get a header of this response
     * 
     *      // Get a header
     *      $accept = $response->getHeader('Content-Type');
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getHeader($key, $default = null);
    
    
    /**
     * Get all headers of this response
     * 
     *      // Get all headers
     *      $headers = $response->getHeaders();
     * 
     * @return \KORD\Mvc\HeaderInterface
     */
    public function getHeaders();

}

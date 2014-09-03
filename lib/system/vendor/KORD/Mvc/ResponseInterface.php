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
     * Returns the length of the body for use with
     * content header
     *
     * @return  integer
     */
    public function contentLength();
    
    /**
     * Get supported HTTP status codes
     * 
     * @return array
     */
    public function getHttpStatusCodes();
    
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
    
    /**
     * Set the HTTP protocol. The standard protocol to use is `HTTP/1.1`.
     * 
     * @param string $protocol
     * @return \KORD\Mvc\ResponseInterface
     */
    public function setProtocol($protocol);

    /**
     * Get the HTTP protocol. The standard protocol to use is `HTTP/1.1`.
     * 
     * @return type
     */
    public function getProtocol();

    /**
     * Set cookie value for this response.
     * 
     *      $response->setCookie('foo', 'bar');
     * 
     * @param string $key
     * @param string $value
     * @return \KORD\Mvc\ResponseInterface
     */
    public function setCookie($key, $value);

    /**
     * Set cookies values for this response.
     * 
     *      $response->setCookies([['foo1', 'bar1'], ['foo2', 'bar2']]);
     * 
     * @param array $array
     * @return \KORD\Mvc\ResponseInterface
     */
    public function setCookies(array $array);
    
    /**
     * Get cookie value for this response. 
     * If cookie doesn't exist - return $default
     * 
     *      $response->getCookie('foo');
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getCookie($key, $default = null);
    
    /**
     * Get all cookies for this response. 
     * 
     * @return array
     */
    public function getCookies();

    /**
     * Sends headers to the php processor, or supplied `$callback` argument.
     * This method formats the headers correctly for output, re-instating their
     * capitalization for transmission.
     *
     * [!!] if you supply a custom header handler via `$callback`, it is
     *  recommended that `$response` is returned
     *
     * @param   boolean         $replace    replace existing value
     * @param   callback        $callback   optional callback to replace PHP header function
     * @return  mixed
     */
    public function sendHeaders($replace = false, $callback = null);

}

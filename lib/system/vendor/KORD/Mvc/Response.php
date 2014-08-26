<?php

namespace KORD\Mvc;

class Response implements ResponseInterface
{

    // HTTP status codes and messages
    protected static $messages = [
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    ];

    /**
     * @var  integer     The response http status
     */
    protected $status = 200;
    
    /**
     * @var  \KORD\Mvc\HeaderInterface  Headers returned in the response
     */
    protected $header;

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
     * @return KORD\Mvc\ResponseInterface
     */
    public function setBody($body)
    {
        $this->body = (string) $body;
        return $this;
    }

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
    public function setStatus($status)
    {
        if (array_key_exists($status, Response::$messages)) {
            $this->status = (int) $status;
            return $this;
        } else {
            throw new \InvalidArgumentException(__METHOD__ . ' unknown status value : ' . $status);
        }
    }

    /**
     * Get HTTP status of this response
     * 
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

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
    public function setHeader($key, $value)
    {
        $this->header[$key] = $value;
        return $this;
    }
    
    /**
     * Set multiple response headers
     * 
     *      // Set multiple headers
     *      $response->setHeaders(['Content-Type' => 'text/html', 'Cache-Control' => 'no-cache']);
     * 
     * @param array $array
     */
    public function setHeaders(array $array)
    {
        $this->header->exchangeArray($key);
        return $this;
    }
    
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
    public function getHeader($key, $default = null)
    {
        return isset($this->header[$key]) ? $this->header[$key] : $default;
    }
    
    
    /**
     * Get all headers of this response
     * 
     *      // Get all headers
     *      $headers = $response->getHeaders();
     * 
     * @return \KORD\Mvc\HeaderInterface
     */
    public function getHeaders()
    {
        return $this->header;
    }

}

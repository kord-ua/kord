<?php

namespace KORD\Mvc;

use \KORD\Helper\CookieInterface;
use \KORD\Mvc\HeaderInterface;

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
    protected $body = '';

    /**
     * @var \KORD\Helper\CookieInterface  Cookie helper
     */
    protected $cookie;

    /**
     * @var  array       Cookies to be returned in the response
     */
    protected $cookies = [];

    /**
     * @var  string      The response protocol
     */
    protected $protocol = 'HTTP/1.1';
    
    /**
     * @var string  default Response charset
     */
    protected $charset = 'utf-8';
    
    /**
     * @var string  default Response content type
     */
    protected $content_type = 'text/html';

    /**
     * Construct new response
     */
    public function __construct(HeaderInterface $header, CookieInterface $cookie, array $config = [])
    {
        $this->header = $header;
        $this->cookie = $cookie;
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key == 'header') {
                    $this->setHeaders($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Outputs the body when cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->body;
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
     * Returns the length of the body for use with
     * content header
     *
     * @return  integer
     */
    public function contentLength()
    {
        return strlen($this->getBody());
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

    /**
     * Set the HTTP protocol. The standard protocol to use is `HTTP/1.1`.
     * 
     * @param string $protocol
     * @return \KORD\Mvc\ResponseInterface
     */
    public function setProtocol($protocol)
    {
        $this->protocol = strtoupper((string) $protocol);
        return $this;
    }

    /**
     * Get the HTTP protocol. The standard protocol to use is `HTTP/1.1`.
     * 
     * @return type
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set cookie value for this response.
     * 
     *      $response->setCookie('foo', 'bar');
     * 
     * @param string $key
     * @param string $value
     * @return \KORD\Mvc\ResponseInterface
     */
    public function setCookie($key, $value)
    {
        if (!is_array($value)) {
            $value = [
                'value' => $value,
                'expiration' => $this->cookie->getExpiration()
            ];
        } elseif (!isset($value['expiration'])) {
            $value['expiration'] = $this->cookie->getExpiration();
        }

        $this->cookies[$key] = $value;

        return $this;
    }

    /**
     * Set cookies values for this response.
     * 
     *      $response->setCookies([['foo1', 'bar1'], ['foo2', 'bar2']]);
     * 
     * @param array $array
     * @return \KORD\Mvc\ResponseInterface
     */
    public function setCookies(array $array)
    {
        reset($array);
        while (list($_key, $_value) = each($array)) {
            $this->setCookie($_key, $_value);
        }
        return $this;
    }
    
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
    public function getCookie($key, $default = null)
    {
        return isset($this->cookies[$key]) ? $this->cookies[$key] : $default;
    }
    
    /**
     * Get all cookies for this response. 
     * 
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

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
    public function sendHeaders($replace = false, $callback = null)
    {
        $protocol = $this->getProtocol();
        $status = $this->getStatus();

        // Create the response header
        $processed_headers = [$protocol . ' ' . $status . ' ' . Response::$messages[$status]];

        // Get the headers array
        $headers = $this->getHeaders()->getArrayCopy();

        foreach ($headers as $header => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $processed_headers[] = implode('-', array_map('ucfirst', explode('-', $header))) . ': ' . $value;
        }
        
        if (!isset($headers['content-type'])) {
            $processed_headers[] = 'Content-Type: ' . $this->content_type . '; charset=' . $this->charset;
        }

        /*if (Core::$expose AND ! isset($headers['x-powered-by'])) {
            $processed_headers[] = 'X-Powered-By: ' . Core::version();
        }*/

        // Get the cookies and apply
        if ($cookies = $this->getCookies()) {
            $processed_headers['Set-Cookie'] = $cookies;
        }

        if (is_callable($callback)) {
            // Use the callback method to set header
            return call_user_func($callback, $this, $processed_headers, $replace);
        } else {
            $this->sendHeadersToPhp($processed_headers, $replace);
            return $this;
        }
    }

    /**
     * Sends the supplied headers to the PHP output buffer. If cookies
     * are included in the message they will be handled appropriately.
     *
     * @param   array   $headers    headers to send to php
     * @param   boolean $replace    replace existing headers
     * @return  \KORD\Mvc\ResponseInterface
     */
    protected function sendHeadersToPhp(array $headers, $replace)
    {
        // If the headers have been sent, get out
        if (headers_sent()) {
            return $this;
        }

        foreach ($headers as $key => $line) {
            if ($key == 'Set-Cookie' AND is_array($line)) {
                // Send cookies
                foreach ($line as $name => $value) {
                    $this->cookie->set($name, $value['value'], $value['expiration']);
                }

                continue;
            }

            header($line, $replace);
        }

        return $this;
    }

}

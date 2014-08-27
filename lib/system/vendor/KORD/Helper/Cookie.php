<?php

namespace KORD\Helper;

/**
 * Cookie helper.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Cookie implements CookieInterface
{

    /**
     * @var  string  Magic salt to add to the cookie
     */
    protected $salt = null;

    /**
     * @var  integer  Number of seconds before the cookie expires
     */
    protected $expiration = 0;

    /**
     * @var  string  Restrict the path that the cookie is available to
     */
    protected $path = '/';

    /**
     * @var  string  Restrict the domain that the cookie is available to
     */
    protected $domain = null;

    /**
     * @var  boolean  Only transmit cookies over secure connections
     */
    protected $secure = false;

    /**
     * @var  boolean  Only transmit cookies over HTTP, disabling Javascript access
     */
    protected $httponly = false;
    
    /**
     * Construct new Cookie instance
     * 
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['salt'])) {
            $this->setSalt($options['salt']);
        }
        
        if (isset($options['expiration'])) {
            $this->setExpiration($options['expiration']);
        }
        
        if (isset($options['path'])) {
            $this->setPath($options['path']);
        }
        
        if (isset($options['domain'])) {
            $this->setDomain($options['domain']);
        }
        
        if (isset($options['secure'])) {
            $this->setSecure($options['secure']);
        }
        
        if (isset($options['httponly'])) {
            $this->setHttponly($options['httponly']);
        }
    }
    
    /**
     * Set cookie salt
     * 
     * @param string $salt
     * @return \KORD\Helper\CookieInterface
     */
    public function setSalt($salt)
    {
        $this->salt = (string) $salt;
        return $this;
    }
    
    /**
     * Get cookie salt
     * 
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Set cookie expiration time in seconds
     * 
     * @param int $seconds
     * @return \KORD\Helper\CookieInterface
     */
    public function setExpiration($seconds)
    {
        $this->expiration = (int) $seconds;
        return $this;
    }
    
    /**
     * Get cookie expiration time in seconds
     * 
     * @return int
     */
    public function getExpiration()
    {
        return $this->expiration;
    }
    
    /**
     * Set cookie restriction path
     * 
     * @param string $path
     * @return \KORD\Helper\CookieInterface
     */
    public function setPath($path)
    {
        $this->path = (string) $path;
        return $this;
    }
    
    /**
     * Get cookie restriction path
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set cookie restriction domain
     * 
     * @param string $domain
     * @return \KORD\Helper\CookieInterface
     */
    public function setDomain($domain)
    {
        $this->domain = (string) $domain;
        return $this;
    }
    
    /**
     * Get cookie restriction domain
     * 
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }
    
    /**
     * Set if cookie should be transmitted only over secure connections
     * 
     * @param bool $flag
     * @return \KORD\Helper\CookieInterface
     */
    public function setSecure($flag)
    {
        $this->secure = (bool) $flag;
        return $this;
    }
    
    /**
     * Get cookie secure option
     * 
     * @return bool
     */
    public function getSecure()
    {
        return $this->secure;
    }
    
    /**
     * Set if cookie should be transmitted only over HTTP, disabling Javascript access
     * 
     * @param bool $flag
     * @return \KORD\Helper\CookieInterface
     */
    public function setHttponly($flag)
    {
        $this->httponly = (bool) $flag;
        return $this;
    }
    
    /**
     * Get cookie httponly option
     * 
     * @return bool
     */
    public function getHttponly()
    {
        return $this->httponly;
    }

    /**
     * Gets the value of a signed cookie. Cookies without signatures will not
     * be returned. If the cookie signature is present, but invalid, the cookie
     * will be deleted.
     *
     *     // Get the "theme" cookie, or use "blue" if the cookie does not exist
     *     $theme = $cookie->get('theme', 'blue');
     *
     * @param   string  $key        cookie name
     * @param   mixed   $default    default value to return
     * @return  string
     */
    public function get($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            // The cookie does not exist
            return $default;
        }

        // Get the cookie value
        $cookie = $_COOKIE[$key];

        // Find the position of the split between salt and contents
        $split = strlen($this->salt($key, null));

        if (isset($cookie[$split]) AND $cookie[$split] === '~') {
            // Separate the salt and the value
            list ($hash, $value) = explode('~', $cookie, 2);

            if ($this->salt($key, $value) === $hash) {
                // Cookie signature is valid
                return $value;
            }

            // The cookie signature is invalid, delete it
            $this->delete($key);
        }

        return $default;
    }

    /**
     * Sets a signed cookie. Note that all cookie values must be strings and no
     * automatic serialization will be performed!
     *
     *     // Set the "theme" cookie
     *     $cookie->set('theme', 'red');
     *
     * @param   string  $name       name of cookie
     * @param   string  $value      value of cookie
     * @param   integer $expiration lifetime in seconds
     * @return  boolean
     */
    public function set($name, $value, $expiration = null)
    {
        if ($expiration === null) {
            // Use the default expiration
            $expiration = $this->getExpiration();
        }

        if ($expiration !== 0) {
            // The expiration is expected to be a UNIX timestamp
            $expiration += time();
        }

        // Add the salt to the cookie value
        $value = $this->salt($name, $value) . '~' . $value;

        return setcookie($name, $value, $expiration, $this->getPath(), $this->getDomain(), $this->getSecure(), $this->getHttponly());
    }

    /**
     * Deletes a cookie by making the value null and expiring it.
     *
     *     $cookie->delete('theme');
     *
     * @param   string  $name   cookie name
     * @return  boolean
     */
    public function delete($name)
    {
        // Remove the cookie
        unset($_COOKIE[$name]);

        // Nullify the cookie and make it expire
        return setcookie($name, null, -86400, $this->getPath(), $this->getDomain(), $this->getSecure(), $this->getHttponly());
    }

    /**
     * Generates a salt string for a cookie based on the name and value.
     *
     *     $salt = $cookie->salt('theme', 'red');
     *
     * @param   string  $name   name of cookie
     * @param   string  $value  value of cookie
     * @return  string
     */
    public function salt($name, $value)
    {
        // Require a valid salt
        if (!$this->getSalt()) {
            throw new \InvalidArgumentException('A valid cookie salt is required. Please set the salt in your bootstrap.php. For more information check the documentation');
        }

        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return sha1($agent . $name . $value . $this->getSalt());
    }

}

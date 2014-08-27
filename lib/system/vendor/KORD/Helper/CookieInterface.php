<?php

namespace KORD\Helper;

/**
 * Cookie helper interface.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface CookieInterface
{

    /**
     * Set cookie salt
     * 
     * @param string $salt
     * @return \KORD\Helper\CookieInterface
     */
    public function setSalt($salt);
    
    /**
     * Get cookie salt
     * 
     * @return string
     */
    public function getSalt();
    
    /**
     * Set cookie expiration time in seconds
     * 
     * @param int $seconds
     * @return \KORD\Helper\CookieInterface
     */
    public function setExpiration($seconds);
    
    /**
     * Get cookie expiration time in seconds
     * 
     * @return int
     */
    public function getExpiration();
    
    /**
     * Set cookie restriction path
     * 
     * @param string $path
     * @return \KORD\Helper\CookieInterface
     */
    public function setPath($path);
    
    /**
     * Get cookie restriction path
     * 
     * @return string
     */
    public function getPath();
    
    /**
     * Set cookie restriction domain
     * 
     * @param string $domain
     * @return \KORD\Helper\CookieInterface
     */
    public function setDomain($domain);
    
    /**
     * Get cookie restriction domain
     * 
     * @return string
     */
    public function getDomain();
    
    /**
     * Set if cookie should be transmitted only over secure connections
     * 
     * @param bool $flag
     * @return \KORD\Helper\CookieInterface
     */
    public function setSecure($flag);
    
    /**
     * Get cookie secure option
     * 
     * @return bool
     */
    public function getSecure();
    
    /**
     * Set if cookie should be transmitted only over HTTP, disabling Javascript access
     * 
     * @param bool $flag
     * @return \KORD\Helper\CookieInterface
     */
    public function setHttponly($flag);
    
    /**
     * Get cookie httponly option
     * 
     * @return bool
     */
    public function getHttponly();
    
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
    public function get($key, $default = null);

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
    public function set($name, $value, $expiration = null);

    /**
     * Deletes a cookie by making the value null and expiring it.
     *
     *     $cookie->delete('theme');
     *
     * @param   string  $name   cookie name
     * @return  boolean
     */
    public function delete($name);

    /**
     * Generates a salt string for a cookie based on the name and value.
     *
     *     $salt = $cookie->salt('theme', 'red');
     *
     * @param   string  $name   name of cookie
     * @param   string  $value  value of cookie
     * @return  string
     */
    public function salt($name, $value);
}

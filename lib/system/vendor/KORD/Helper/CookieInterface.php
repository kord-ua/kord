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

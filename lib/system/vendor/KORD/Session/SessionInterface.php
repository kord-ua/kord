<?php

namespace KORD\Session;

/**
 * Base session class.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface SessionInterface
{

    /**
     * Returns the current session array. The returned array can also be
     * assigned by reference.
     *
     *     // Get a copy of the current session data
     *     $data = $session->asArray();
     *
     *     // Assign by reference for modification
     *     $data =& $session->asArray();
     *
     * @return  array
     */
    public function & asArray();

    /**
     * Get the current session id, if the session supports it.
     *
     *     $id = $session->id();
     *
     * [!!] Not all session types have ids.
     *
     * @return  string
     */
    public function id();

    /**
     * Get the current session cookie name.
     *
     *     $name = $session->cookieName();
     *
     * @return  string
     */
    public function cookieName();

    /**
     * Get a variable from the session array.
     *
     *     $foo = $session->get('foo');
     *
     * @param   string  $key        variable name
     * @param   mixed   $default    default value to return
     * @return  mixed
     */
    public function get($key, $default = null);

    /**
     * Get and delete a variable from the session array.
     *
     *     $bar = $session->getOnce('bar');
     *
     * @param   string  $key        variable name
     * @param   mixed   $default    default value to return
     * @return  mixed
     */
    public function getOnce($key, $default = null);

    /**
     * Set a variable in the session array.
     *
     *     $session->set('foo', 'bar');
     *
     * @param   string  $key    variable name
     * @param   mixed   $value  value
     * @return  \KORD\Session\SessionInterface
     */
    public function set($key, $value);

    /**
     * Set a variable by reference.
     *
     *     $session->bind('foo', $foo);
     *
     * @param   string  $key    variable name
     * @param   mixed   $value  referenced value
     * @return  \KORD\Session\SessionInterface
     */
    public function bind($key, & $value);

    /**
     * Removes a variable in the session array.
     *
     *     $session->delete('foo');
     *
     * @param   string  $key    variable name(s)
     * @return  \KORD\Session\SessionInterface
     */
    public function delete($key);

    /**
     * Loads existing session data.
     *
     *     $session->read();
     *
     * @param   string  $id session id
     * @return  void
     */
    public function read($id = null);

    /**
     * Generates a new session id and returns it.
     *
     *     $id = $session->regenerate();
     *
     * @param bool $delete_old_session  delete old session file?
     * @return  string
     */
    public function regenerate($delete_old_session = false);

    /**
     * Sets the last_active timestamp and saves the session.
     *
     *     $session->write();
     *
     * [!!] Any errors that occur during session writing will be logged,
     * but not displayed, because sessions are written after output has
     * been sent.
     *
     * @return  boolean
     */
    public function write();

    /**
     * Completely destroy the current session.
     *
     *     $success = $session->destroy();
     *
     * @return  boolean
     */
    public function destroy();

    /**
     * Restart the session.
     *
     *     $success = $session->restart();
     *
     * @return  boolean
     */
    public function restart();

}

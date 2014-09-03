<?php

namespace KORD\Session;

/**
 * Cookie-based session class.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Cookie extends SessionAbstract
{

    /**
     * @param   string  $id  session id
     * @return  string
     */
    protected function readSession($id = null)
    {
        return $this->cookie->get($this->cookie_name, null);
    }

    /**
     * @return  null
     */
    protected function regenerateSession($delete_old_session = false)
    {
        // Cookie sessions have no id
        return null;
    }

    /**
     * @return  bool
     */
    protected function writeSession()
    {
        return $this->cookie->set($this->cookie_name, $this->__toString(), $this->lifetime);
    }

    /**
     * @return  bool
     */
    protected function restartSession()
    {
        return true;
    }

    /**
     * @return  bool
     */
    protected function destroySession()
    {
        return $this->cookie->delete($this->cookie_name);
    }

}

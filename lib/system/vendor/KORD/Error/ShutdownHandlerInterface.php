<?php

namespace KORD\Error;

/**
 * Shutdown handler interface.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface ShutdownHandlerInterface
{

    /**
     * Catches errors that are not caught by the error handler, such as E_PARSE.
     *
     * @return  void
     */
    public function handler();

}

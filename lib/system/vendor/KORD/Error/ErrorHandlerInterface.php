<?php

namespace KORD\Error;

/**
 * Error handler interface.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface ErrorHandlerInterface
{

    /**
     * PHP error handler, converts all errors into ErrorExceptions. This handler
     * respects error_reporting settings.
     *
     * @throws  ErrorException
     * @return  true
     */
    public function handler($code, $error, $file = null, $line = null);

}

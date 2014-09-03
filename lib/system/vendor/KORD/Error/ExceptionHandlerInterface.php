<?php

namespace KORD\Error;

/**
 * Exception handler interface.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface ExceptionHandlerInterface
{

    /**
     * Get a single line of text representing the exception:
     *
     * Error [ Code ]: Message ~ File [ Line ]
     *
     * @param   \Exception  $e
     * @return  string
     */
    public function text(\Exception $e);

    /**
     * Inline exception handler, displays the error message, source of the
     * exception, and the stack trace of the error.
     *
     * @param   \Exception  $e
     * @return  void
     */
    public function handler(\Exception $e);

    /**
     * Exception handler, logs the exception and generates a Response object
     * for display.
     *
     * @param   \Exception  $e
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function process(\Exception $e);

    /**
     * Get a Response object representing the exception
     *
     * @param   \Exception  $e
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function getResponse(\Exception $e);

}

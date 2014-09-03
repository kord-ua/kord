<?php

namespace KORD\Error;

/**
 * Shutdown handler class.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class ShutdownHandler implements ShutdownHandlerInterface
{

    /**
     * @var  array  Types of errors to display at shutdown
     */
    protected static $shutdown_errors = [E_PARSE, E_ERROR, E_USER_ERROR];

    /**
     * @var \KORD\Error\ExceptionHandlerInterface 
     */
    protected $exception_handler;

    /**
     * Construct new exception handler
     * 
     * @param \KORD\Mvc\ResponseInterface $response
     * @param \KORD\Mvc\ViewFactoryInterface $view_factory
     * @param type $error_view
     */
    public function __construct(ExceptionHandlerInterface $exception_handler)
    {
        $this->exception_handler = $exception_handler;
    }

    /**
     * Catches errors that are not caught by the error handler, such as E_PARSE.
     *
     * @return  void
     */
    public function handler()
    {
        if ($error = error_get_last() AND in_array($error['type'], ShutdownHandler::$shutdown_errors)) {
            // Clean the output buffer
            ob_get_level() AND ob_clean();

            // Fake an exception for nice debugging
            $this->exception_handler->handler(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));

            // Shutdown now to avoid a "death loop"
            exit(1);
        }
    }

}

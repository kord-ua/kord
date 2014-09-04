<?php

namespace KORD\Error;

use KORD\Error\DebugInterface;
use KORD\Log\LoggerInterface;
use KORD\Mvc\ResponseFactoryInterface;
use KORD\Mvc\ViewFactoryInterface;

/**
 * Exception handler class.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class ExceptionHandler implements ExceptionHandlerInterface
{

    /**
     * @var  array  PHP error code => human readable name
     */
    protected static $php_errors = [
        E_ERROR => 'Fatal Error',
        E_USER_ERROR => 'User Error',
        E_PARSE => 'Parse Error',
        E_WARNING => 'Warning',
        E_USER_WARNING => 'User Warning',
        E_STRICT => 'Strict',
        E_NOTICE => 'Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
    ];

    /**
     * @var  string  error rendering view
     */
    protected $error_view = 'kord/error';

    /**
     * @var string  default response charset
     */
    protected $charset = 'utf-8';

    /**
     * @var string  default response content type
     */
    protected $content_type = 'text/html';

    /**
     * @var \KORD\Error\DebugInterface 
     */
    protected $debug;

    /**
     * @var \KORD\Log\LoggerInterface 
     */
    protected $logger;

    /**
     * @var \KORD\Mvc\ResponseFactoryInterface 
     */
    protected $response_factory;

    /**
     * @var \KORD\Mvc\ViewFactoryInterface 
     */
    protected $view_factory;

    /**
     * Construct new exception handler
     * 
     * @param \KORD\Mvc\ResponseFactoryInterface $response
     * @param \KORD\Mvc\ViewFactoryInterface $view_factory
     * @param type $error_view
     */
    public function __construct(ResponseFactoryInterface $response_factory, ViewFactoryInterface $view_factory, DebugInterface $debug, LoggerInterface $logger, array $config = [])
    {
        $this->response_factory = $response_factory;
        $this->view_factory = $view_factory;
        $this->debug = $debug;
        $this->logger = $logger;

        if (isset($config['error_view'])) {
            $this->error_view = $config['error_view'];
        }

        if (isset($config['charset'])) {
            $this->charset = $config['charset'];
        }

        if (isset($config['content_type'])) {
            $this->content_type = $config['content_type'];
        }
    }

    /**
     * Get a single line of text representing the exception:
     *
     * Error [ Code ]: Message ~ File [ Line ]
     *
     * @param   \Exception  $e
     * @return  string
     */
    public function text(\Exception $e)
    {
        return sprintf('%s [ %s ]: %s ~ %s [ %d ]', get_class($e), $e->getCode(), strip_tags($e->getMessage()), $this->debug->path($e->getFile()), $e->getLine());
    }

    /**
     * Inline exception handler, displays the error message, source of the
     * exception, and the stack trace of the error.
     *
     * @param   \Exception  $e
     * @return  void
     */
    public function handler(\Exception $e)
    {
        $response = $this->process($e);

        // Send the response to the browser
        echo $response->sendHeaders()->getBody();

        exit(1);
    }

    /**
     * Exception handler, logs the exception and generates a Response object
     * for display.
     *
     * @param   \Exception  $e
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function process(\Exception $e)
    {
        try {
            // Log the exception
            $this->log($e);
            // Generate the response
            $response = $this->getResponse($e);

            return $response;
        } catch (\Exception $e) {
            /**
             * Things are going *really* badly for us, We now have no choice
             * but to bail. Hard.
             */
            // Clean the output buffer if one exists
            ob_get_level() AND ob_clean();

            // Set the Status code to 500, and Content-Type to text/plain.
            header('Content-Type: text/plain; charset=' . $this->charset, true, 500);

            echo $this->text($e);

            exit(1);
        }
    }

    /**
     * Logs an exception.
     *
     * @param   \Exception  $e
     * @param   string      $level
     * @return  void
     */
    public function log(\Exception $e, $format = "body in file:line", $level = LOG_EMERG)
    {
        if (is_object($this->logger)) {

            // Add this exception to the log
            $trace = $e->getTrace();

            if (defined('DOCROOT') AND isset($trace[0]['file']) AND strpos($trace[0]['file'], DOCROOT) === 0) {
                $trace[0]['file'] = 'DOCROOT' . DIRECTORY_SEPARATOR . substr($trace[0]['file'], strlen(DOCROOT));
            }

            $message = [
                'body' => $this->text($e),
                'file' => isset($trace[0]['file']) ? $trace[0]['file'] : null,
                'line' => isset($trace[0]['line']) ? $trace[0]['line'] : null,
                'class' => isset($trace[0]['class']) ? $trace[0]['class'] : null,
                'function' => isset($trace[0]['function']) ? $trace[0]['function'] : null
            ];

            $error = strtr($format, array_filter($message, 'is_scalar'));

            $this->logger->log($level, $error);

            // Add the trace to the log
            $message['body'] = $e->getTraceAsString();

            $strace = PHP_EOL . strtr($format, array_filter($message, 'is_scalar'));

            $this->logger->log(LOG_DEBUG, $strace);

            // Make sure the logs are written
            if (method_exists($this->logger, 'write')) {
                $this->logger->write();
            }
        }
    }

    /**
     * Get a Response object representing the exception
     *
     * @param   \Exception  $e
     * @return  \KORD\Mvc\ResponseInterface
     */
    public function getResponse(\Exception $e)
    {
        try {
            // Get the exception information
            $class = get_class($e);
            $code = $e->getCode();
            $message = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $trace = $e->getTrace();

            /**
             * \HTTP\Exceptions are constructed in the \HTTP\Exception::factory()
             * method. We need to remove that entry from the trace and overwrite
             * the variables from above.
             */
            /* if ($e instanceof HTTPException AND $trace[0]['function'] == 'factory') {
              extract(array_shift($trace));
              } */


            if ($e instanceof \ErrorException) {
                /**
                 * If XDebug is installed, and this is a fatal error,
                 * use XDebug to generate the stack trace
                 */
                if (function_exists('xdebug_get_function_stack') AND $code == E_ERROR) {
                    $trace = array_slice(array_reverse(xdebug_get_function_stack()), 4);

                    foreach ($trace as & $frame) {
                        /**
                         * XDebug pre 2.1.1 doesn't currently set the call type key
                         * http://bugs.xdebug.org/view.php?id=695
                         */
                        if (!isset($frame['type'])) {
                            $frame['type'] = '??';
                        }

                        // XDebug also has a different name for the parameters array
                        if (isset($frame['params']) AND ! isset($frame['args'])) {
                            $frame['args'] = $frame['params'];
                        }
                    }
                }

                if (isset(ExceptionHandler::$php_errors[$code])) {
                    // Use the human-readable error name
                    $code = ExceptionHandler::$php_errors[$code];
                }
            }

            /**
             * The stack trace becomes unmanageable inside PHPUnit.
             *
             * The error view ends up several GB in size, taking
             * serveral minutes to render.
             */
            if (defined('PHPUnit_MAIN_METHOD')) {
                $trace = array_slice($trace, 0, 2);
            }

            // Instantiate the error view.
            $view = $this->view_factory->newInstance($this->error_view, get_defined_vars())
                    ->set('debug', $this->debug)
                    ->set('charset', $this->charset);

            // Prepare the response object.
            $response = $this->response_factory->newInstance();

            $status_codes = $response->getHttpStatusCodes();

            // Set the response status
            if ($code AND in_array($code, $status_codes)) {
                $response->setStatus($code);
            } else {
                $response->setStatus(500);
            }

            // Set the response headers
            $response->setHeader('Content-Type', $this->content_type . '; charset=' . $this->charset);
            $response->setHeader('Cache-Control', 'no-cache');

            // HTTP redirect
            if ($code >= 300 AND $code < 400) {
                if ($code == 305 AND strpos($message, '://') === false) {
                    throw new \Exception('An absolute URI to the proxy server must be specified');
                }
                $response->setHeader('Location', $message);
            }

            // HTTP 401 Unauthorized
            if ($code == 401) {
                $response->setHeader('www-authenticate', $message);
            }

            // HTTP 405 Method Not Allowed
            if ($code == 405) {
                $response->setHeader('allow', $message);
            }

            // Set the response body
            $response->setBody($view->render());
        } catch (\Exception $e) {
            /**
             * Things are going badly for us, Lets try to keep things under control by
             * generating a simpler response object.
             */
            $response = $this->response;
            $response->setStatus(500);
            $response->setHeader('Content-Type', 'text/plain');
            $response->setBody($this->text($e));
        }

        return $response;
    }

}

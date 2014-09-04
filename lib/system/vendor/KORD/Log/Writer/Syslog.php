<?php

namespace KORD\Log\Writer;

/**
 * Syslog log writer.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Syslog extends WriterAbstract
{

    /**
     * @var  string  The syslog identifier
     */
    protected $ident;

    /**
     * Creates a new syslog logger.
     *
     * @link    http://www.php.net/manual/function.openlog
     *
     * @param   string  $ident      syslog identifier
     * @param   int     $facility   facility to log to
     * @return  void
     */
    public function __construct($ident = 'KORDPHP', $facility = LOG_USER, $timestamp = null, $timezone = null, $strace_level = null)
    {
        $this->ident = $ident;

        // Open the connection to syslog
        openlog($this->ident, LOG_CONS, $facility);
        
        parent::__construct($timestamp, $timezone, $strace_level);
    }

    /**
     * Writes each of the messages into the syslog.
     *
     * @param   array   $messages
     * @return  void
     */
    public function write(array $messages)
    {
        foreach ($messages as $message) {
            syslog($message['level'], $message['body']);
        }
    }

    /**
     * Closes the syslog connection
     *
     * @return  void
     */
    public function __destruct()
    {
        // Close connection to syslog
        closelog();
    }

}

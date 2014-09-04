<?php

namespace KORD\Log\Writer;

/**
 * Log writer abstract class. All [Log] writers must extend this class.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
abstract class WriterAbstract implements WriterInterface
{

    /**
     * @var  string  timestamp format for log entries.
     */
    protected $timestamp = 'Y-m-d H:i:s';

    /**
     * @var  string  timezone for log entries
     *
     * Defaults to date_default_timezone_get()
     */
    protected $timezone;
    
    /**
     * Create new Log Writer
     * 
     * @param string $timestamp
     * @param string $timezone
     * @param int $strace_level
     */
    public function __construct($timestamp = null, $timezone = null)
    {
        if ($timestamp !== null) {
            $this->timestamp = $timestamp;
        }
        
        if ($timezone !== null) {
           $this->timezone = $timezone; 
        }
    }

    /**
     * Allows the writer to have a unique key when stored.
     *
     *     echo $writer;
     *
     * @return  string
     */
    final public function __toString()
    {
        return spl_object_hash($this);
    }

    /**
     * Formats a log entry.
     *
     * @param   array   $message
     * @param   string  $format
     * @return  string
     */
    public function formatMessage(array $message, $format = "time --- level: message")
    {
        $tz = new \DateTimeZone($this->timezone ? $this->timezone : date_default_timezone_get());
        $time = new \DateTime('@'.$message['time'], $tz);

        if ($time->getTimeZone()->getName() !== $tz->getName()) {
            $time->setTimeZone($tz);
        }

        $message['time'] = $time->format($this->timestamp);
        $message['level'] = strtoupper($message['level']);

        $string = strtr($format, array_filter($message, 'is_scalar'));

        return $string;
    }

}

<?php

namespace KORD\Log;

use KORD\Log\Writer\WriterInterface;

/**
 * Message logging with observer-based log writing.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Logger extends AbstractLogger implements LoggerInterface
{
    
    /**
     * @var  array  list of supported log levels
     */
    protected $levels = [
        LOG_EMERG => LogLevel::EMERGENCY,
        LOG_ALERT => LogLevel::ALERT,
        LOG_CRIT => LogLevel::CRITICAL,
        LOG_ERR => LogLevel::ERROR,
        LOG_WARNING => LogLevel::WARNING,
        LOG_NOTICE => LogLevel::NOTICE,
        LOG_INFO => LogLevel::INFO,
        LOG_DEBUG => LogLevel::DEBUG
    ];

    /**
     * @var  boolean  immediately write when logs are added
     */
    protected $write_on_add = false;

    /**
     * @var  array  list of added messages
     */
    protected $messages = [];

    /**
     * @var  array  list of log writers
     */
    protected $writers = [];

    /**
     * Construct \KORD\Log\Logger object
     */
    public function __construct(array $writers = null, $write_on_add = false)
    {
        if (!empty($writers)) {
            foreach ($writers as $writer) {
                $this->attach($writer());
            }
        }
        
        $this->write_on_add = $write_on_add;
        // Write the logs at shutdown
        register_shutdown_function([$this, 'write']);
    }

    /**
     * Attaches a log writer, and optionally limits the levels of messages that
     * will be written by the writer.
     *
     *     $logger->attach($writer);
     *
     * @param   \KORD\Log\Writer\WriterInterface  $writer     instance
     * @param   mixed  $levels     array of messages levels to write OR max level to write
     * @return  \KORD\Log\Logger
     */
    public function attach(WriterInterface $writer, $levels = [])
    {
        $this->writers["{$writer}"] = [
            'object' => $writer,
            'levels' => $levels
        ];

        return $this;
    }

    /**
     * Detaches a log writer. The same writer object must be used.
     *
     *     $logger->detach($writer);
     *
     * @param   \KORD\Log\Writer\WriterInterface  $writer instance
     * @return  \KORD\Log\Logger
     */
    public function detach(WriterInterface $writer)
    {
        // Remove the writer
        unset($this->writers["{$writer}"]);

        return $this;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        // PSR-3 states that $message should be a string
        $message = (string) $message;

        // PSR-3 states that we must throw a
        // \InvalidArgumentException if we don't
        // recognize the level
        if ((is_string($level) AND !in_array($level, $this->levels)) 
                OR (is_int($level) AND !in_array($level, array_keys($this->levels)))) {
            throw new \InvalidArgumentException("Unknown severity level");
        }

        if ($context) {
            $message = $this->interpolate($message, $context);
        }

        // Create a new message
        $this->messages[] = [
            'time' => time(),
            'level' => is_string($level) ? $level : $this->levels[$level],
            'message' => $message
        ];

        if ($this->write_on_add) {
            // Write logs as they are added
            $this->write();
        }

        return $this;
    }

    /**
     * Interpolates context values into the message placeholders.
     * According to PSR-3
     */
    protected function interpolate($message, array $context = [])
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * Write and clear all of the messages.
     *
     *     $logger->write();
     *
     * @return  void
     */
    public function write()
    {
        if (empty($this->messages)) {
            // There is nothing to write, move along
            return;
        }

        // Import all messages locally
        $messages = $this->messages;

        // Reset the messages array
        $this->messages = [];

        foreach ($this->writers as $writer) {
            if (empty($writer['levels'])) {
                // Write all of the messages
                $writer['object']->write($messages);
            } else {
                // Filtered messages
                $filtered = [];

                foreach ($messages as $message) {
                    if (in_array($message['level'], $writer['levels'])) {
                        // Writer accepts this kind of message
                        $filtered[] = $message;
                    }
                }

                // Write the filtered messages
                $writer['object']->write($filtered);
            }
        }
    }

}

<?php

namespace KORD\Log\Writer;

/**
 * Log writer interface. All [Log] writers must implement this interface.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface WriterInterface
{
    
    /**
     * Write an array of messages.
     *
     *     $writer->write($messages);
     *
     * @param   array   $messages
     * @return  void
     */
    public function write(array $messages);

    /**
     * Formats a log entry.
     *
     * @param   array   $message
     * @param   string  $format
     * @return  string
     */
    public function formatMessage(array $message, $format = "time --- level: body in file:line");

}

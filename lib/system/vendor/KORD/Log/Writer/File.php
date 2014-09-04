<?php

namespace KORD\Log\Writer;

/**
 * File log writer. Writes out messages and stores them in a YYYY/MM directory.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class File extends WriterAbstract
{

    /**
     * @var  string  Directory to place log files in
     */
    protected $directory;

    /**
     * Creates a new file logger. Checks that the directory exists and
     * is writable.
     *
     *     $writer = new \KORD\Log\Writer\File($directory);
     *
     * @param   string  $directory  log directory
     * @return  void
     */
    public function __construct($directory, $timestamp = null, $timezone = null)
    {
        if (!is_dir($directory) OR ! is_writable($directory)) {
            if (defined('DOCROOT') AND strpos($directory, DOCROOT) === 0) {
                $directory = 'DOCROOT' . DIRECTORY_SEPARATOR . substr($directory, strlen(DOCROOT));
            }
            throw new \Exception("Directory {$directory} must be writable");
        }

        // Determine the directory path
        $this->directory = realpath($directory) . DIRECTORY_SEPARATOR;
        
        parent::__construct($timestamp, $timezone);
    }

    /**
     * Writes each of the messages into the log file. The log file will be
     * appended to the `YYYY/MM/DD.log.php` file, where YYYY is the current
     * year, MM is the current month, and DD is the current day.
     *
     *     $writer->write($messages);
     *
     * @param   array   $messages
     * @return  void
     */
    public function write(array $messages)
    {
        // Set the yearly directory name
        $directory = $this->directory . date('Y');

        if (!is_dir($directory)) {
            // Create the yearly directory
            mkdir($directory, 02777);

            // Set permissions (must be manually set to fix umask issues)
            chmod($directory, 02777);
        }

        // Add the month to the directory
        $directory .= DIRECTORY_SEPARATOR . date('m');

        if (!is_dir($directory)) {
            // Create the monthly directory
            mkdir($directory, 02777);

            // Set permissions (must be manually set to fix umask issues)
            chmod($directory, 02777);
        }

        // Set the name of the log file
        $filename = $directory . DIRECTORY_SEPARATOR . date('d') . EXT;

        if (!file_exists($filename)) {
            // Create the log file
            file_put_contents($filename, FILE_SECURITY . ' ?>' . PHP_EOL);

            // Allow anyone to write to log files
            chmod($filename, 0666);
        }

        foreach ($messages as $message) {
            // Write each message into the log file
            file_put_contents($filename, PHP_EOL . $this->formatMessage($message), FILE_APPEND);
        }
    }

}

<?php

namespace KORD\Error;

/**
 * Debug helper interface.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface DebugInterface
{
    /**
     * Returns an HTML string of debugging information about any number of
     * variables, each wrapped in a "pre" tag:
     *
     *     // Displays the type and value of each variable
     *     echo $debug->vars($foo, $bar, $baz);
     *
     * @param   mixed   $var,...    variable to debug
     * @return  string
     */
    public function vars();

    /**
     * Returns an HTML string of information about a single variable.
     *
     * Borrows heavily on concepts from the Debug class of [Nette](http://nettephp.com/).
     *
     * @param   mixed   $value              variable to dump
     * @param   integer $length             maximum length of strings
     * @param   integer $level_recursion    recursion limit
     * @return  string
     */
    public function dump($value, $length = 128, $level_recursion = 10);

    /**
     * Removes application, system, modpath, or docroot from a filename,
     * replacing them with the plain text equivalents. Useful for debugging
     * when you want to display a shorter path.
     *
     *     // Displays SYSPATH/i18n/en.php
     *     echo $debug->path($filesystem->findFile('i18n', 'en'));
     *
     * @param   string  $file   path to debug
     * @return  string
     */
    public function path($file);

    /**
     * Returns an HTML string, highlighting a specific line of a file, with some
     * number of lines padded above and below.
     *
     *     // Highlights the current line of the current file
     *     echo $debug->source(__FILE__, __LINE__);
     *
     * @param   string  $file           file to open
     * @param   integer $line_number    line number to highlight
     * @param   integer $padding        number of padding lines
     * @return  string   source of file
     * @return  false    file is unreadable
     */
    public function source($file, $line_number, $padding = 5);

    /**
     * Returns an array of HTML strings that represent each step in the backtrace.
     *
     *     // Displays the entire current backtrace
     *     echo implode('<br/>', $debug->trace());
     *
     * @param   array   $trace
     * @return  string
     */
    public function trace(array $trace = null);
}

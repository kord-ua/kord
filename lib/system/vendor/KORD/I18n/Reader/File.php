<?php

namespace KORD\I18n\Reader;

use KORD\Filesystem\FileSystemInterface;
use KORD\Helper\ArrInterface;

/**
 * I18n File Reader
 * 
 * Uses KORD i18n files, the code is a slightly modified version of `Kohana_I18n` class.

 * @copyright  (c) 2012 Korney Czukowski
 * @copyright  (c) 2014 Andriy Strepetov
 * @license    MIT License
 */
class File implements ReaderInterface
{

    /**
     * @var array  translation cache
     */
    protected $cache = [];
    
    /**
     * @var string  directory name with translation files
     */
    protected $directory = 'i18n';
    
    /**
     * @var KORD\Helper\ArrInterface  array helper 
     */
    protected $arr;

    /**
     * @var KORD\Filesystem\FileSystemInterface  file system
     */
    protected $filesystem;

    /**
     * 
     * @param \KORD\Helper\ArrInterface $arr
     * @param \KORD\Filesystem\FileSystemInterface $filesystem
     * @param  string  $directory
     */
    public function __construct(ArrInterface $arr, FileSystemInterface $filesystem, $directory = 'i18n')
    {
        $this->arr = $arr;
        $this->filesystem = $filesystem;
        $this->directory = $directory;
    }

    /**
     * Returns the translation(s) of a string or null if there's no translation for the string.
     * No parameters are replaced.
     * 
     * @param   string   text to translate
     * @param   string   target language
     * @return  mixed
     */
    public function get($string, $lang)
    {
        // Load the translation table for this language
        $table = $this->load($lang);

        // Return the translated string if it exists
        if (isset($table[$string])) {
            return $table[$string];
        } elseif (($translation = $this->arr->path($table, $string)) !== null) {
            return $translation;
        }
        return null;
    }

    /**
     * Loads the translation table for a given language.
     * 
     *     // Get all defined Spanish messages
     *     $messages = $i18n->load('es-es');
     * 
     * @param   string  language to load
     * @return  array
     */
    private function load($lang)
    {
        if (isset($this->cache[$lang])) {
            return $this->cache[$lang];
        }

        // New translation table
        $table = [];

        // Split the language: language, region, locale, etc
        $parts = explode('-', $lang);

        do {
            // Create a path for this set of parts
            $path = implode(DIRECTORY_SEPARATOR, $parts);
            $files = $this->filesystem->findFile($this->directory, $path, null, true);
            if ($files) {
                $tables = [];
                foreach ($files as $file) {
                    // Merge the language strings into the sub table
                    $tables = array_merge_recursive($tables, $this->filesystem->load($file));
                }

                // Append the sub table, preventing less specific language
                // files from overloading more specific files
                $table += $tables;
            }

            // Remove the last part
            array_pop($parts);
        } while ($parts);

        // Cache the translation table locally
        return $this->cache[$lang] = $table;
    }

}

<?php

namespace KORD\FileSystem;

/**
 * Cascade file system operations
 */
class Cascade implements FileSystemInterface
{

    /**
     * @var  array   Include paths that are used to find files/directories
     */
    protected $paths = [];

    /**
     * @var  array   File path cache, used when $caching is true
     */
    protected $files = [];

    /**
     * @var  array   Dir path cache, used when $caching is true
     */
    protected $dirs = [];

    /**
     * @var  boolean  Has the file path cache changed during this execution?
     */
    protected $files_changed = false;

    /**
     * @var  boolean  Whether to use internal caching for findFile
     */
    protected $caching = false;

    /**
     * Sets the included paths
     *
     * @param array $paths Included paths
     * @return  array
     */
    public function setIncludePaths(array $paths)
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * Returns the the currently active include paths, including the
     * application, system, and each module's path.
     *
     * @return  array
     */
    public function getIncludePaths()
    {
        return $this->paths;
    }

    /**
     * Sets caching on/off (true/false)
     *
     * @param bool $flag  on/off (true/false)
     * @return  array
     */
    public function setCaching($flag)
    {
        $this->caching = (bool) $flag;
        return $this;
    }

    /**
     * Returns current $caching setting
     *
     * @return  bool
     */
    public function getCaching()
    {
        return $this->caching;
    }

    /**
     * Searches for a directory in the file system, and returns the path 
     * to the directory.
     * 
     * When the `$array` flag is set to true, an array of all the directories 
     * that match that path in the file system will be returned.
     *
     *     // Returns an absolute path to views
     *     $path = $filesystem->findDir('views');
     *
     *     // Returns an array of all the "views" directories
     *     $paths = $filesystem->findDir('views', true);
     *
     * @param   string  $dir    directory name (views, i18n, classes, extensions, etc.)
     * @param   boolean $array  return an array of dirs?
     * @return  array   a list of directories when $array is true
     * @return  string  single dir path
     */
    public function findDir($dir, $array = false)
    {
        if ($this->caching === true AND isset($this->dirs[$dir . ($array ? '_array' : '_path')])) {
            // This path has been cached
            return $this->dirs[$dir . ($array ? '_array' : '_path')];
        }

        if ($array OR $dir === 'config' OR $dir === 'i18n' OR $dir === 'messages') {
            // Include paths must be searched in reverse
            $paths = array_reverse($this->paths);

            // Array of files that have been found
            $found = [];

            foreach ($paths as $path) {
                if (is_dir($path . $dir)) {
                    // This path has a file, add it to the list
                    $found[] = $path . $dir;
                }
            }
        } else {
            // The file has not been found yet
            $found = false;

            foreach ($this->paths as $path) {
                if (is_dir($path . $dir)) {
                    // A path has been found
                    $found = $path . $dir;

                    // Stop searching
                    break;
                }
            }
        }

        if ($this->caching === true) {
            // Add the path to the cache
            $this->dirs[$dir . ($array ? '_array' : '_path')] = $found;

            // Files have been changed
            $this->files_changed = true;
        }

        return $found;
    }

    /**
     * Searches for a file in the [Cascading Filesystem](kord/files), and
     * returns the path to the file that has the highest precedence, so that it
     * can be included.
     *
     * When searching the "config", "messages", or "i18n" directories, or when
     * the `$array` flag is set to true, an array of all the files that match
     * that path in the [Cascading Filesystem](kord/files) will be returned.
     * These files will return arrays which must be merged together.
     *
     *     // Returns an absolute path to views/template.php
     *     $path = $filesystem->findFile('views', 'template');
     *
     *     // Returns an absolute path to media/css/style.css
     *     $path = $filesystem->findFile('media', 'css/style', 'css');
     *
     *     // Returns an array of all the "mimes" configuration files
     *     $paths = $filesystem->findFile('config', 'mimes', null, true);
     *
     * @param   string  $dir    directory name (views, i18n, classes, extensions, etc.)
     * @param   string  $file   filename with subdirectory
     * @param   string  $ext    extension to search for
     * @param   boolean $array  return an array of files?
     * @return  array   a list of files when $array is true
     * @return  string  single file path
     */
    public function findFile($dir, $file, $ext = null, $array = false)
    {
        if ($ext === null) {
            // Use the default extension
            if (defined('EXT')) {
                $ext = EXT;
            } else {
                $ext = '.php';
            }
        } elseif ($ext) {
            // Prefix the extension with a period
            $ext = ".{$ext}";
        } else {
            // Use no extension
            $ext = '';
        }

        // Create a partial path of the filename
        $path = $dir . DIRECTORY_SEPARATOR . $file . $ext;

        if ($this->caching === true AND isset($this->files[$path . ($array ? '_array' : '_path')])) {
            // This path has been cached
            return $this->files[$path . ($array ? '_array' : '_path')];
        }

        if ($array OR $dir === 'config' OR $dir === 'i18n' OR $dir === 'messages') {
            // Include paths must be searched in reverse
            $paths = array_reverse($this->paths);

            // Array of files that have been found
            $found = [];

            foreach ($paths as $dir) {
                if (is_file($dir . $path)) {
                    // This path has a file, add it to the list
                    $found[] = $dir . $path;
                }
            }
        } else {
            // The file has not been found yet
            $found = false;

            foreach ($this->paths as $dir) {
                if (is_file($dir . $path)) {
                    // A path has been found
                    $found = $dir . $path;

                    // Stop searching
                    break;
                }
            }
        }

        if ($this->caching === true) {
            // Add the path to the cache
            $this->files[$path . ($array ? '_array' : '_path')] = $found;

            // Files have been changed
            $this->files_changed = true;
        }

        return $found;
    }

    /**
     * Recursively finds all of the files in the specified directory at any
     * location in the [Cascading Filesystem](kord/files), and returns an
     * array of all the files found, sorted alphabetically.
     *
     *     // Find all view files.
     *     $views = $filesystem->listFiles('views');
     *
     * @param   string  $directory  directory name
     * @param   array   $paths      list of paths to search
     * @return  array
     */
    public function listFiles($directory = null, array $paths = null)
    {
        if ($directory !== null) {
            // Add the directory separator
            $directory .= DIRECTORY_SEPARATOR;
        }

        if ($paths === null) {
            // Use the default paths
            $paths = $this->paths;
        }

        // Create an array for the files
        $found = [];

        foreach ($paths as $path) {
            if (is_dir($path . $directory)) {
                // Create a new directory iterator
                $dir = new \DirectoryIterator($path . $directory);

                foreach ($dir as $file) {
                    // Get the file name
                    $filename = $file->getFilename();

                    if ($filename[0] === '.' OR $filename[strlen($filename) - 1] === '~') {
                        // Skip all hidden files and UNIX backup files
                        continue;
                    }

                    // Relative filename is the array key
                    $key = $directory . $filename;

                    if ($file->isDir()) {
                        if ($sub_dir = $this->listFiles($key, $paths)) {
                            if (isset($found[$key])) {
                                // Append the sub-directory list
                                $found[$key] += $sub_dir;
                            } else {
                                // Create a new sub-directory list
                                $found[$key] = $sub_dir;
                            }
                        }
                    } else {
                        if (!isset($found[$key])) {
                            // Add new files to the list
                            $found[$key] = realpath($file->getPathName());
                        }
                    }
                }
            }
        }

        // Sort the results alphabetically
        ksort($found);

        return $found;
    }

    /**
     * Loads a file within a totally empty scope and returns the output:
     *
     *     $foo = $filesystem->load('foo.php');
     *
     * @param   string  $file
     * @return  mixed
     */
    public function load($file)
    {
        return include $file;
    }

}

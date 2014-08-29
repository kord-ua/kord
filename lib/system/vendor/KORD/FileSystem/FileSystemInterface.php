<?php

namespace KORD\Filesystem;

/**
 * File system operations interface
 */
interface FileSystemInterface
{
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
    public function findDir($dir, $array = false);

    /**
     * Searches for a file in the file system, and returns the path to the file.
     * 
     * When the `$array` flag is set to true, an array of all the files 
     * that match that path in the file system will be returned.
     *
     * If no extension is given, the default extension (`EXT` constant) 
     * will be used.
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
    public function findFile($dir, $file, $ext = null, $array = false);

    /**
     * Recursively finds all of the files in the specified directory at any
     * location in the file system, and returns an array of all the files found, 
     * sorted alphabetically.
     *
     *     // Find all view files.
     *     $views = $filesystem->listFiles('views');
     *
     * @param   string  $directory  directory name
     * @param   array   $paths      list of paths to search
     * @return  array
     */
    public function listFiles($directory = null, array $paths = null);
    
    /**
     * Loads a file within a totally empty scope and returns the output:
     *
     *     $foo = $filesystem->load('foo.php');
     *
     * @param   string  $file
     * @return  mixed
     */
    public function load($file);
    
    /**
     * Writes content to a file
     * 
     *      $foo = $filesystem->save('foo.php');
     * 
     * @param string $file
     * @param string $contents
     */
    public function save($file, $contents);

}

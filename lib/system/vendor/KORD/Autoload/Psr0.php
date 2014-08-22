<?php

namespace KORD\Autoload;

use KORD\Filesystem\FileSystemInterface;

/**
 * PSR-0 autoloader
 */
class Psr0
{
    
    /**
     * @var \KORD\Filesystem\FileSystemInterface 
     */
    protected $filesystem;

    /**
     * Construct new object
     * 
     * @param \KORD\Filesystem\FileSystemInterface $filesystem
     */
    public function __construct(FileSystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Provides auto-loading support of classes that follow KORD's [class
     * naming conventions](kord/conventions#class-names-and-file-location).
     * See [Loading Classes](kord/autoloading) for more information.
     *
     *     // Loads classes/My/Class/Name.php
     *     $psr0->autoLoad('My\Class\Name');
     *
     * or with a custom directory:
     *
     *     // Loads vendor/My/Class/Name.php
     *     $psr0->autoLoad('My_Class_Name', 'vendor');
     *
     * You should never have to call this function, as simply calling a class
     * will cause it to be called.
     *
     * This function must be enabled as an autoloader in the bootstrap:
     *
     *     spl_autoload_register([$psr0, 'autoLoad']);
     *
     * @param   string  $class      Class name
     * @param   string  $directory  Directory to load from
     * @return  boolean
     */
    public function autoLoad($class, $directory = 'vendor')
    {
        // Transform the class name according to PSR-0
        $class = ltrim($class, '\\');
        $file = '';
        $namespace = '';

        if ($last_namespace_position = strripos($class, '\\')) {
            $namespace = substr($class, 0, $last_namespace_position);
            $class = substr($class, $last_namespace_position + 1);
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $file .= str_replace('_', DIRECTORY_SEPARATOR, $class);

        if ($path = $this->filesystem->findFile($directory, $file)) {
            // Load the class file
            require $path;

            // Class has been found
            return true;
        }

        // Class is not in the filesystem
        return false;
    }

}

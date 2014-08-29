<?php

namespace KORD\Config\File;

use KORD\Config\ReaderInterface;
use KORD\Filesystem\FileSystemInterface;
use KORD\Helper\ArrInterface;

/**
 * File-based configuration reader. Multiple configuration directories can be
 * used by attaching multiple instances of this class to [\KORD\Config\Repository].
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Reader implements ReaderInterface
{

    /**
     * The directory where config files are located
     * @var string
     */
    protected $directory = '';
    
    /**
     * @var \KORD\Helper\ArrInterface  Array helper
     */
    protected $arr;
    
    /**
     * @var \KORD\Filesystem\FileSystemInterface 
     */
    protected $filesystem;

    /**
     * Creates a new file reader using the given directory as a config source
     *
     * @param string    $directory  Configuration directory to search
     */
    public function __construct(FileSystemInterface $filesystem, ArrInterface $arr, $directory = 'config')
    {
        $this->filesystem = $filesystem;
        $this->arr = $arr;
        // Set the configuration directory name
        $this->directory = trim($directory, '/');
    }

    /**
     * Load and merge all of the configuration files in this group.
     *
     *     $config->load($name);
     *
     * @param   string  $group  configuration group name
     * @return  $this   current object
     * @uses    \KORD\Core::load
     */
    public function load($group)
    {
        $config = [];

        if ($files = $this->filesystem->findFile($this->directory, $group, null, true)) {
            foreach ($files as $file) {
                // Merge each file to the configuration array
                $config = $this->arr->merge($config, $this->filesystem->load($file));
            }
        }

        return $config;
    }

}

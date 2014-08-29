<?php

namespace KORD\Config\File;

use KORD\Config\WriterInterface;

/**
 * File-based configuration reader/writer. Multiple configuration directories 
 * can be used by attaching multiple instances of this class to [\KORD\Config].
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Writer extends Reader implements WriterInterface
{

    /**
     * Writes the passed config for $group
     *
     * Returns chainable instance on success or throws
     * \Exception on failure
     *
     * @param string      $group  The config group
     * @param array       $config The configuration to write
     * @return bool
     */
    public function write($group, $config)
    {
        $file = $this->filesystem->findFile($this->directory, $group, null);

        if (!$file) {
            throw new \Exception("No file for config group {$group} is found in the file system");
        }
        
        $this->filesystem->save($file, 'return ' . var_export($config, true) . ';');
    }

}

<?php

namespace KORD\Config;

/**
 * Interface for config readers
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface ReaderInterface extends SourceInterface
{

    /**
     * Tries to load the specified configuration group
     *
     * Returns false if group does not exist or an array if it does
     *
     * @param  string $group Configuration group
     * @return bool|array
     */
    public function load($group);
}

<?php

namespace KORD\Config;

/**
 * Config group interface
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface GroupInterface
{

    /**
     * Alias for getArrayCopy()
     *
     * @return array Array copy of the group's config
     */
    public function asArray();

    /**
     * Returns the config group's name
     *
     * @return string The group name
     */
    public function groupName();

    /**
     * Get a variable from the configuration or return the default value.
     *
     *     $value = $config->get($key);
     *
     * @param   string  $key        array key
     * @param   mixed   $default    default value
     * @return  mixed
     */
    public function get($key, $default = null);

    /**
     * Sets a value in the configuration array.
     *
     *     $config->set($key, $new_value);
     *
     * @param   string  $key    array key
     * @param   mixed   $value  array value
     * @return  $this
     */
    public function set($key, $value);

}

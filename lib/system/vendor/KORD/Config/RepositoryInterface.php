<?php

namespace KORD\Config;

/**
 * Config repository interface
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface RepositoryInterface
{

    /**
     * Attach a configuration reader. By default, the reader will be added as
     * the first used reader. However, if the reader should be used only when
     * all other readers fail, use `false` for the second parameter.
     *
     *     $config->attach($reader);        // Try first
     *     $config->attach($reader, false); // Try last
     *
     * @param   \KORD\Config\SourceInterface    $source instance
     * @param   bool            $first  add the reader as the first used object
     * @return  $this
     */
    public function attach(SourceInterface $source, $first = true);

    /**
     * Detach a configuration reader.
     *
     *     $config->detach($reader);
     *
     * @param   \KORD\Config\SourceInterface   $source instance
     * @return  $this
     */
    public function detach(SourceInterface $source);

    /**
     * Load a configuration group. Searches all the config sources, merging all the
     * directives found into a single config group.  Any changes made to the config
     * in this group will be mirrored across all writable sources.
     *
     *     $array = $config->load($name);
     *
     * See [\KORD\Config\Group] for more info
     *
     * @param   string  $group  configuration group name
     * @return  \KORD\Config\Group
     * @throws  \Exception
     */
    public function load($group);

    /**
     * Copy one configuration group to all of the other writers.
     *
     *     $config->copy($name);
     *
     * @param   string  $group  configuration group name
     * @return  $this
     */
    public function copy($group);

    /**
     * Callback used by the config group to store changes made to configuration
     *
     * @param string    $group  Group name
     * @param array     $config The new config
     * @return $this    Chainable instance
     */
    public function write($group, $config);

}

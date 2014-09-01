<?php

namespace KORD\Config;

use KORD\Helper\ArrInterface;

/**
 * Wrapper for configuration arrays. Multiple configuration readers can be
 * attached to allow loading configuration from files, database, etc.
 *
 * Configuration directives cascade across config sources in the same way that
 * files cascade across the filesystem.
 *
 * Directives from sources high in the sources list will override ones from those
 * below them.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Repository implements RepositoryInterface
{

    /**
     * @var array  Configuration readers
     */
    protected $sources = [];

    /**
     * @var array  Array of config groups
     */
    protected $groups = [];

    /**
     * @var \KORD\Helper\ArrInterface  Array helper
     */
    protected $arr;

    /**
     * @var object  Config group construction closure
     */
    protected $group_closure;

    /**
     * Construct new Config repository
     * 
     * @param object $group_closure           Config group construction closure
     * @param \KORD\Helper\ArrInterface $arr  array helper
     * @param array $sources                  array of Configuration readers
     */
    public function __construct($group_closure, ArrInterface $arr, array $sources = null)
    {
        $this->group_closure = $group_closure;
        $this->arr = $arr;
        if (!empty($sources)) {
            foreach ($sources as $source) {
                $this->attach($source());
            }
        }
    }

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
    public function attach(SourceInterface $source, $first = true)
    {
        if ($first === true) {
            // Place the log reader at the top of the stack
            array_unshift($this->sources, $source);
        } else {
            // Place the reader at the bottom of the stack
            $this->sources[] = $source;
        }

        // Clear any cached groups
        $this->groups = [];

        return $this;
    }

    /**
     * Detach a configuration reader.
     *
     *     $config->detach($reader);
     *
     * @param   \KORD\Config\SourceInterface   $source instance
     * @return  $this
     */
    public function detach(SourceInterface $source)
    {
        if (($key = array_search($source, $this->sources)) !== false) {
            // Remove the writer
            unset($this->sources[$key]);
        }

        return $this;
    }

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
    public function load($group)
    {
        if (!count($this->sources)) {
            throw new \Exception('No configuration sources attached');
        }

        if (empty($group)) {
            throw new \Exception("Need to specify a config group");
        }

        if (!is_string($group)) {
            throw new \Exception("Config group must be a string");
        }

        if (strpos($group, '.') !== false) {
            // Split the config group and path
            list($group, $path) = explode('.', $group, 2);
        }

        if (isset($this->groups[$group])) {
            if (isset($path)) {
                return $this->arr->path($this->groups[$group], $path, null, '.');
            }
            return $this->groups[$group];
        }

        $config = [];

        // We search from the "lowest" source and work our way up
        $sources = array_reverse($this->sources);

        foreach ($sources as $source) {
            if ($source instanceof ReaderInterface) {
                if ($source_config = $source->load($group)) {
                    $config = $this->arr->merge($config, $source_config);
                }
            }
        }

        $closure = $this->group_closure;
        $this->groups[$group] = $closure($this, $group, $config);

        if (isset($path)) {
            return $this->arr->path($config, $path, null, '.');
        }

        return $this->groups[$group];
    }

    /**
     * Copy one configuration group to all of the other writers.
     *
     *     $config->copy($name);
     *
     * @param   string  $group  configuration group name
     * @return  $this
     */
    public function copy($group)
    {
        // Load the configuration group
        $config = $this->load($group);

        $this->write($group, $config->asArray());

        return $this;
    }

    /**
     * Callback used by the config group to store changes made to configuration
     *
     * @param string    $group  Group name
     * @param array     $config The new config
     * @return $this    Chainable instance
     */
    public function write($group, $config)
    {
        foreach ($this->sources as $source) {
            if (!($source instanceof WriterInterface)) {
                continue;
            }

            // Copy each value in the config
            $source->write($group, $config);
        }

        return $this;
    }

}

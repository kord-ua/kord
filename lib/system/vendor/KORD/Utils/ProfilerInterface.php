<?php

namespace KORD\Utils;

/**
 * Profiler interface
 */
interface ProfilerInterface
{

    /**
     * Starts a new benchmark and returns a unique token. The returned token
     * _must_ be used when stopping the benchmark.
     *
     *     $token = $profiler->start('test', 'profiler');
     *
     * @param   string  $group  group name
     * @param   string  $name   benchmark name
     * @return  string
     */
    public function start($group, $name);

    /**
     * Stops a benchmark.
     *
     *     $profiler->stop($token);
     *
     * @param   string  $token
     * @return  void
     */
    public function stop($token);

    /**
     * Deletes a benchmark. If an error occurs during the benchmark, it is
     * recommended to delete the benchmark to prevent statistics from being
     * adversely affected.
     *
     *     $profiler->delete($token);
     *
     * @param   string  $token
     * @return  void
     */
    public function delete($token);

    /**
     * Returns all the benchmark tokens by group and name as an array.
     *
     *     $groups = $profiler->groups();
     *
     * @return  array
     */
    public function groups();

    /**
     * Gets the min, max, average and total of a set of tokens as an array.
     *
     *     $stats = $profiler->stats($tokens);
     *
     * @param   array   $tokens profiler tokens
     * @return  array   min, max, average, total
     * @uses    $this->total
     */
    public function stats(array $tokens);

    /**
     * Gets the min, max, average and total of profiler groups as an array.
     *
     *     $stats = $profiler->groupStats('test');
     *
     * @param   mixed   $groups single group name string, or array with group names; all groups by default
     * @return  array   min, max, average, total
     * @uses    $this->groups
     * @uses    $this->stats
     */
    public function groupStats($groups = null);

    /**
     * Gets the total execution time and memory usage of a benchmark as a list.
     *
     *     list($time, $memory) = $profiler->total($token);
     *
     * @param   string  $token
     * @return  array   execution time, memory
     */
    public function total($token);

    /**
     * Gets the total application run time and memory usage. Caches the result
     * so that it can be compared between requests.
     *
     *     list($time, $memory) = $profiler->application();
     *
     * @return  array  execution time, memory
     * @uses    \KORD\Core::cache
     */
    public function application();

}

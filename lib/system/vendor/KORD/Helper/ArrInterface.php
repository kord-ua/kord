<?php

namespace KORD\Helper;

/**
 * Array helper interface.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface ArrInterface
{

    /**
     * Tests if an array is associative or not.
     *
     *     // Returns true
     *     Arr::isAssoc(['username' => 'john.doe']);
     *
     *     // Returns false
     *     Arr::isAssoc(['foo', 'bar']);
     *
     * @param   array   $array  array to check
     * @return  boolean
     */
    public function isAssoc(array $array);

    /**
     * Test if a value is an array with an additional check for array-like objects.
     *
     *     // Returns true
     *     Arr::isArray([]);
     *     Arr::isArray(new ArrayObject);
     *
     *     // Returns false
     *     Arr::isArray(false);
     *     Arr::isArray('not an array!');
     *     Arr::isArray(Database::instance());
     *
     * @param   mixed   $value  value to check
     * @return  boolean
     */
    public function isArray($value);

    /**
     * Gets a value from an array using a dot separated path.
     *
     *     // Get the value of $array['foo']['bar']
     *     $value = Arr::path($array, 'foo.bar');
     *
     * Using a wildcard "*" will search intermediate arrays and return an array.
     *
     *     // Get the values of "color" in theme
     *     $colors = Arr::path($array, 'theme.*.color');
     *
     *     // Using an array of keys
     *     $colors = Arr::path($array, ['theme', '*', 'color']);
     *
     * @param   array   $array      array to search
     * @param   mixed   $path       key path string (delimiter separated) or array of keys
     * @param   mixed   $default    default value if the path is not set
     * @param   string  $delimiter  key path delimiter
     * @return  mixed
     */
    public function path($array, $path, $default = null, $delimiter = null);

    /**
     * Set a value on an array by path.
     *
     * @see Arr::path()
     * @param array   $array     Array to update
     * @param string  $path      Path
     * @param mixed   $value     Value to set
     * @param string  $delimiter Path delimiter
     */
    public function setPath(& $array, $path, $value, $delimiter = null);

    /**
     * Retrieve a single key from an array. If the key does not exist in the
     * array, the default value will be returned instead.
     *
     *     // Get the value "username" from $_POST, if it exists
     *     $username = Arr::get($_POST, 'username');
     *
     *     // Get the value "sorting" from $_GET, if it exists
     *     $sorting = Arr::get($_GET, 'sorting');
     *
     * @param   array   $array      array to extract from
     * @param   string  $key        key name
     * @param   mixed   $default    default value
     * @return  mixed
     */
    public function get($array, $key, $default = null);

    /**
     * Retrieves multiple paths from an array. If the path does not exist in the
     * array, the default value will be added instead.
     *
     *     // Get the values "username", "password" from $_POST
     *     $auth = Arr::extract($_POST, ['username', 'password']);
     *
     *     // Get the value "level1.level2a" from $data
     *     $data = ['level1' => ['level2a' => 'value 1', 'level2b' => 'value 2']];
     *     Arr::extract($data, ['level1.level2a', 'password']);
     *
     * @param   array  $array    array to extract paths from
     * @param   array  $paths    list of path
     * @param   mixed  $default  default value
     * @return  array
     */
    public function extract($array, array $paths, $default = null);

    /**
     * Retrieves muliple single-key values from a list of arrays.
     *
     *     // Get all of the "id" values from a result
     *     $ids = Arr::pluck($result, 'id');
     *
     * [!!] A list of arrays is an array that contains arrays, eg: array(array $a, array $b, array $c, ...)
     *
     * @param   array   $array  list of arrays to check
     * @param   string  $key    key to pluck
     * @return  array
     */
    public function pluck($array, $key);

    /**
     * Adds a value to the beginning of an associative array.
     *
     *     // Add an empty value to the start of a select list
     *     Arr::unshift($array, 'none', 'Select a value');
     *
     * @param   array   $array  array to modify
     * @param   string  $key    array key name
     * @param   mixed   $val    array value
     * @return  array
     */
    public function unshift(array & $array, $key, $val);

    /**
     * Recursive version of [array_map](http://php.net/array_map), applies one or more
     * callbacks to all elements in an array, including sub-arrays.
     *
     *     // Apply "strip_tags" to every element in the array
     *     $array = Arr::map('strip_tags', $array);
     *
     *     // Apply $this->filter to every element in the array
     *     $array = Arr::map([[$this,'filter']], $array);
     *
     *     // Apply strip_tags and $this->filter to every element
     *     $array = Arr::map(['strip_tags',[$this,'filter']], $array);
     *
     * [!!] Because you can pass an array of callbacks, if you wish to use an array-form callback
     * you must nest it in an additional array as above. Calling Arr::map([$this,'filter'], $array)
     * will cause an error.
     * [!!] Unlike `array_map`, this method requires a callback and will only map
     * a single array.
     *
     * @param   mixed   $callbacks  array of callbacks to apply to every element in the array
     * @param   array   $array      array to map
     * @param   array   $keys       array of keys to apply to
     * @return  array
     */
    public function map($callbacks, $array, $keys = null);

    /**
     * Recursively merge two or more arrays. Values in an associative array
     * overwrite previous values with the same key. Values in an indexed array
     * are appended, but only when they do not already exist in the result.
     *
     * Note that this does not work the same as [array_merge_recursive](http://php.net/array_merge_recursive)!
     *
     *     $john = ['name' => 'john', 'children' => ['fred', 'paul', 'sally', 'jane']];
     *     $mary = ['name' => 'mary', 'children' => ['jane']];
     *
     *     // John and Mary are married, merge them together
     *     $john = Arr::merge($john, $mary);
     *
     *     // The output of $john will now be:
     *     ['name' => 'mary', 'children' => ['fred', 'paul', 'sally', 'jane']]
     *
     * @param   array  $array1      initial array
     * @param   array  $array2 ,...  array to merge
     * @return  array
     */
    public function merge($array1, $array2);

    /**
     * Overwrites an array with values from input arrays.
     * Keys that do not exist in the first array will not be added!
     *
     *     $a1 = ['name' => 'john', 'mood' => 'happy', 'food' => 'bacon'];
     *     $a2 = ['name' => 'jack', 'food' => 'tacos', 'drink' => 'beer'];
     *
     *     // Overwrite the values of $a1 with $a2
     *     $array = Arr::overwrite($a1, $a2);
     *
     *     // The output of $array will now be:
     *     ['name' => 'jack', 'mood' => 'happy', 'food' => 'tacos']
     *
     * @param   array   $array1 master array
     * @param   array   $array2 input arrays that will overwrite existing values
     * @return  array
     */
    public function overwrite($array1, $array2);

    /**
     * Creates a callable function and parameter list from a string representation.
     * Note that this function does not validate the callback string.
     *
     *     // Get the callback function and parameters
     *     list($func, $params) = Arr::callback('Foo::bar(apple,orange)');
     *
     *     // Get the result of the callback
     *     $result = call_user_func_array($func, $params);
     *
     * @param   string  $str    callback string
     * @return  array   function, params
     */
    public function callback($str);

    /**
     * Convert a multi-dimensional array into a single-dimensional array.
     *
     *     $array = ['set' => ['one' => 'something'], 'two' => 'other'];
     *
     *     // Flatten the array
     *     $array = Arr::flatten($array);
     *
     *     // The array will now be
     *     ['one' => 'something', 'two' => 'other'];
     *
     * [!!] The keys of array values will be discarded.
     *
     * @param   array   $array  array to flatten
     * @return  array
     */
    public function flatten($array);

}

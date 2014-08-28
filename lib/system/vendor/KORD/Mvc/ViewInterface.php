<?php

namespace KORD\Mvc;

/**
 * Acts as an object wrapper for HTML pages with embedded PHP, called "views".
 * Variables can be assigned with the view object and referenced locally within
 * the view.
 * 
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface ViewInterface
{

    /**
     * Sets the view filename.
     *
     *     $view->setFilename($file);
     *
     * @param   string  $file   view filename
     * @return  $this
     * @throws  \InvalidArgumentException
     */
    public function setFilename($file);

    /**
     * Assigns a variable by name. Assigned values will be available as a
     * variable within the view file:
     *
     *     // This value can be accessed as $foo within the view
     *     $view->set('foo', 'my value');
     *
     * You can also use an array to set several values at once:
     *
     *     // Create the values $food and $beverage in the view
     *     $view->set(['food' => 'bread', 'beverage' => 'water']);
     *
     * @param   string  $key    variable name or an array of variables
     * @param   mixed   $value  value
     * @return  \KORD\Mvc\ViewInterface
     */
    public function set($key, $value = null);

    /**
     * Get view variables
     * 
     * @param string $key
     */
    public function get($key = null);

    /**
     * Assigns a value by reference. The benefit of binding is that values can
     * be altered without re-setting them. It is also possible to bind variables
     * before they have values. Assigned values will be available as a
     * variable within the view file:
     *
     *     // This reference can be accessed as $ref within the view
     *     $view->bind('ref', $bar);
     *
     * @param   string  $key    variable name
     * @param   mixed   $value  referenced variable
     * @return  \KORD\Mvc\ViewInterface
     */
    public function bind($key, & $value);

    /**
     * Renders the view object to a string. Global and local data are merged
     * and extracted to create local variables within the view file.
     *
     *     $output = $view->render();
     *
     * [!!] Global variables with the same key name as local variables will be
     * overwritten by the local variable.
     *
     * @param   string  $file   view filename
     * @return  string
     * @throws  \InvalidArgumentException
     * @uses    \KORD\Mvc\View::capture
     */
    public function render($file = null);
    
    /**
     * Captures the output that is generated when a view is included.
     * The view data will be extracted to make local variables. This method
     * is static to prevent object scope resolution.
     *
     *     $output = $view->capture($file, $data);
     *
     * @param   string  $view_filename   filename
     * @param   array   $view_data       variables
     * @return  string
     */
    public function capture($view_filename, array $view_data);

}

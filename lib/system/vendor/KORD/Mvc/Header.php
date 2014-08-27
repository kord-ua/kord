<?php

namespace KORD\Mvc;

class Header extends \ArrayObject implements HeaderInterface
{
    
    /**
     * Constructor method for [\KORD\Mvc\Header]. Uses the standard constructor
     * of the parent `ArrayObject` class.
     *
     *     $header_object = new \KORD\Mvc\Header(['x-powered-by' => '..., 'expires' => '...']);
     *
     * @param   mixed   $input          Input array
     * @param   int     $flags          Flags
     * @param   string  $iterator_class The iterator class to use
     */
    public function __construct(array $input = [], $flags = 0, $iterator_class = 'ArrayIterator')
    {
        /**
         * @link http://www.w3.org/Protocols/rfc2616/rfc2616.html
         *
         * HTTP header declarations should be treated as case-insensitive
         */
        $input = array_change_key_case((array) $input, CASE_LOWER);

        parent::__construct($input, $flags, $iterator_class);
    }

    /**
     * Overloads the `ArrayObject::exchangeArray()` method to ensure that
     * all keys are changed to lowercase.
     *
     * @param   mixed   $input
     * @return  array
     */
    public function exchangeArray($input)
    {
        /**
         * @link http://www.w3.org/Protocols/rfc2616/rfc2616.html
         *
         * HTTP header declarations should be treated as case-insensitive
         */
        $input = array_change_key_case((array) $input, CASE_LOWER);

        return parent::exchangeArray($input);
    }

}

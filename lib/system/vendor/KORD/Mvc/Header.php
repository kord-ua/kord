<?php

namespace KORD\Mvc;

class Header extends \ArrayObject implements HeaderInterface
{

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

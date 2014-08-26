<?php

namespace KORD\Mvc;

interface HeaderInterface
{

    /**
     * Overloads the `ArrayObject::exchangeArray()` method to ensure that
     * all keys are changed to lowercase.
     *
     * @param   mixed   $input
     * @return  array
     */
    public function exchangeArray($input);
    
}

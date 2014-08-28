<?php

namespace KORD\Mvc;

interface ViewFactoryInterface
{

    /**
     * Create new request instance
     * 
     * @param string $file
     * @return \KORD\Mvc\ViewInterface
     */
    public function newInstance($file = null);

}

<?php

namespace KORD\Mvc;

interface ViewFactoryInterface
{

    /**
     * Create new request instance
     * 
     * @param string $file
     * @param string $data
     * @return \KORD\Mvc\ViewInterface
     */
    public function newInstance($file = null, array $data = null);

}

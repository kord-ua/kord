<?php

namespace KORD\Mvc;

interface RequestFactoryInterface
{

    /**
     * Create new request instance
     * 
     * @param string $uri
     * @return \KORD\Mvc\RequestInterface
     * @throws \Exception
     */
    public function newInstance($uri = null);

}

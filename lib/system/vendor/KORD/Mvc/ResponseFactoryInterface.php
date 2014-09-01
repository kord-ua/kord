<?php

namespace KORD\Mvc;

interface ResponseFactoryInterface
{

    /**
     * Create new response instance
     * 
     * @return \KORD\Mvc\ResponseInterface
     */
    public function newInstance();

}

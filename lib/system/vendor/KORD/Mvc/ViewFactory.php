<?php

namespace KORD\Mvc;

class ViewFactory implements ViewFactoryInterface
{
    
    /**
     * @var object 
     */
    protected $closure;

    /**
     * Construct new view factory
     * 
     * @param object $closure
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    /**
     * Create new request instance
     * 
     * @param string $file
     * @param string $data
     * @return \KORD\Mvc\ViewInterface
     */
    public function newInstance($file = null, array $data = null)
    {
        $closure = $this->closure;
        $view = $closure();
        
        if ($file !== null) {
            $view->setFilename($file);
        }
        
        if ($data !== null) {
            $view->set($data);
        }

        return $view;
    }

}

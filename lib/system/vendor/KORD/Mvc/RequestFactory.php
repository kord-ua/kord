<?php

namespace KORD\Mvc;

class RequestFactory implements RequestFactoryInterface
{

    /**
     * @var Aura\Router\Router 
     */
    protected $router;
    
    /**
     * @var object 
     */
    protected $closure;

    /**
     * Construct new request factory
     * 
     * @param object $router
     * @param object $closure
     */
    public function __construct($router, $closure)
    {
        $this->router = $router;
        $this->closure = $closure;
    }

    /**
     * Create new request instance
     * 
     * @param string $uri
     * @return \KORD\Mvc\RequestInterface
     * @throws \Exception
     */
    public function newInstance($uri = null)
    {
        if ($uri === null) {
            $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        }
        $route = $this->router->match($uri, $_SERVER);

        if (!$route) {
            throw new \Exception('Requested URL not found on this server', 404);
        } else {
            $closure = $this->closure;
            $request = $closure();
            $request->setController($route->params['controller']);
            $request->setAction($route->params['action']);

            return $request;
        }
    }

}

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
     * @var array  Array of client closures
     */
    protected $clients;

    /**
     * Construct new request factory
     * 
     * @param object $router
     * @param object $closure
     */
    public function __construct($router, $closure, array $clients)
    {
        $this->router = $router;
        $this->closure = $closure;
        $this->clients = $clients;
    }

    /**
     * Create new request instance
     * 
     * @param string $uri
     * @return \KORD\Mvc\RequestInterface
     * @throws \Exception
     */
    public function newInstance($uri = null, array $client_params = [], $external_client = null)
    {
        if ($uri === null) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

        if (strpos($uri, '://') === false) {
            $route = $this->router->match(trim($uri, '/'), $_SERVER);

            if (!$route) {
                throw new \Exception('Requested URL not found on this server', 404);
            } else {
                $closure = $this->closure;
                $client = $this->clients['internal'];
                $request = $closure($client($client_params));
                $request->setController($route->params['controller']);
                $request->setAction($route->params['action']);
                $request->setUri($uri);

                return $request;
            }
        } else {
            $closure = $this->closure;
            $client = $this->clients[$external_client];
            $request = $closure($client($client_params));
            
            return $request;
        }
    }

}

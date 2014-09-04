<?php

namespace KORD\Mvc;

class Controller implements ControllerInterface
{
    
    /**
     * @var KORD\Mvc\RequestFactoryInterface
     */
    protected $request_factory;
    
    /**
     * @var KORD\Mvc\RequestInterface 
     */
    protected $request;
    
    /**
     * @var KORD\Mvc\ResponseInterface 
     */
    protected $response;
    
    /**
     * @var KORD\Mvc\ViewFactoryInterface
     */
    protected $view_factory;
    
    /**
     * @var KORD\Mvc\ViewInterface
     */
    protected $view_global;
    
    /**
     * @var KORD\Helper\ArrInterface
     */
    protected $arr;
    
    /**
     * @var KORD\Config\RepositoryInterface 
     */
    protected $config;

    /**
     * @var KORD\Helper\CookieInterface
     */
    protected $cookie;
    
    /**
     * @var KORD\Helper\DateInterface 
     */
    protected $date;

    /**
     * @var KORD\Crypt\EncryptInterface
     */
    protected $encrypt;

    /**
     * @var KORD\Crypt\HashInterface
     */
    protected $hash;

    /**
     * @var KORD\I18n\RepositoryInterface 
     */
    protected $i18n;
    
    /**
     * @var KORD\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var KORD\Crypt\PasswordHash\PasswordHashInterface 
     */
    protected $password_hash;
    
    /**
     * @var KORD\Helper\RandomInterface 
     */
    protected $random;
    
    /**
     * @var KORD\Session\SessionInterface 
     */
    protected $session;

    /**
     * @var KORD\Helper\UTF8Interface 
     */
    protected $utf8;

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     * Arr helper injection
     * 
     * @param \KORD\Helper\ArrInterface $arr
     */
    public function setArr(\KORD\Helper\ArrInterface $arr)
    {
        $this->arr = $arr;
    }
    
    /**
     * Config injection
     * 
     * @param \KORD\Config\RepositoryInterface $config
     */
    public function setConfig(\KORD\Config\RepositoryInterface $config)
    {
        $this->config = $config;
    }
    
    /**
     * Cookie helper injection
     * 
     * @param \KORD\Helper\CookieInterface $cookie
     */
    public function setCookie(\KORD\Helper\CookieInterface $cookie)
    {
        $this->cookie = $cookie;
    }
    
    /**
     * Date helper injection
     * 
     * @param \KORD\Helper\DateInterface $date
     */
    public function setDate(\KORD\Helper\DateInterface $date)
    {
        $this->date = $date;
    }
    
    /**
     * Encrypt injection
     * 
     * @param \KORD\Crypt\EncryptInterface $encrypt
     */
    public function setEncrypt(\KORD\Crypt\EncryptInterface $encrypt)
    {
        $this->encrypt = $encrypt;
    }
    
    /**
     * Hash injection
     * 
     * @param \KORD\Crypt\HashInterface $hash
     */
    public function setHash(\KORD\Crypt\HashInterface $hash)
    {
        $this->hash = $hash;
    }
    
    /**
     * I18n helper injection
     * 
     * @param \KORD\I18n\RepositoryInterface $i18n
     */
    public function setI18n(\KORD\I18n\RepositoryInterface $i18n)
    {
        $this->i18n = $i18n;
    }
    
    /**
     * Logger injection
     * 
     * @param \KORD\Log\LoggerInterface $logger
     */
    public function setLogger(\KORD\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * PasswordHash injection
     * 
     * @param \KORD\Crypt\PasswordHash\PasswordHashInterface $password_hash
     */
    public function setPasswordHash(\KORD\Crypt\PasswordHash\PasswordHashInterface $password_hash)
    {
        $this->password_hash = $password_hash;
    }
    
    /**
     * Request factory injection
     * 
     * @param \KORD\Mvc\RequestFactoryInterface $request_factory
     */
    public function setRequestFactory(RequestFactoryInterface $request_factory)
    {
        $this->request_factory = $request_factory;
    }
    
    /**
     * Random helper injection
     * 
     * @param \KORD\Helper\RandomInterface $random
     */
    public function setRandom(\KORD\Helper\RandomInterface $random)
    {
        $this->random = $random;
    }
    
    /**
     * Session injection
     * 
     * @param \KORD\Session\SessionInterface $session
     */
    public function setSession(\KORD\Session\SessionInterface $session)
    {
        $this->session = $session;
    }
    
    /**
     * UTF8 helper injection
     * 
     * @param \KORD\Helper\UTF8Interface $utf8
     */
    public function setUtf8(\KORD\Helper\UTF8Interface $utf8)
    {
        $this->utf8 = $utf8;
    }
    
    /**
     * View factory injection
     * 
     * @param \KORD\Mvc\ViewFactoryInterface $view_factory
     */
    public function setViewFactory(\KORD\Mvc\ViewFactoryInterface $view_factory)
    {
        $this->view_factory = $view_factory;
    }
    
    /**
     * Global view injection
     * 
     * @param \KORD\Mvc\ViewInterface $view_global
     */
    public function setViewGlobal(\KORD\Mvc\ViewInterface $view_global)
    {
        $this->view_global = $view_global;
    }
    
    /**
     * Execute request, return response
     * 
     * @return KORD\Mvc\ResponseInterface 
     */
    public function execute()
    {
        $this->before();
        
        $action = $this->request->getAction() . 'Action';
        
        $this->{$action}();
        
        $this->after();
        
        return $this->response;
    }
    
    /**
     * Automatically executed before the controller action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        // Nothing by default
    }

    /**
     * Automatically executed after the controller action. Can be used to apply
     * transformation to the response, add extra output, and execute
     * other custom code.
     *
     * @return  void
     */
    public function after()
    {
        // Nothing by default
    }

}

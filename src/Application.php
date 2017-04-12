<?php

namespace phalconer\app;

/**
 * @const APPLICATION_ENV Current application stage:
 *        production, staging, development, testing
 */
defined('APPLICATION_ENV') or define('APPLICATION_ENV', 'development');

use Phalcon\Config;
use Phalcon\Di;
use Phalcon\DiInterface;
use Phalcon\Di\FactoryDefault;
use Phalcon\Error\Handler as ErrorHandler;
use Phalcon\Loader;
use Phalcon\Mvc\Application as MvcApplication;
use phalconer\app\provider\DefaultServiceProviderFactory;

class Application
{
    /**
     * The internal application core.
     * @var \Phalcon\Application
     */
    private $app;

    /**
     * @var DiInterface
     */
    private $di;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        /* Init Phalcon loader */
        $configLoader = $config->get('loader', false);
        if ($configLoader !== false) {
            $loader = new Loader();
            if (isset($configLoader['namespaces'])) {
                $loader->registerNamespaces($configLoader['namespaces']->toArray())->register();
            }
        }
        
        /* Init Phalcon DI */
        $this->di = new FactoryDefault;
        Di::setDefault($this->di);
        $this->di->setShared('app', $this);
        $this->di->setShared('config', $config);
                
        /** @noinspection PhpIncludeInspection */
        $services = $config->get('services', false);
        if ($services) {
            $this->initializeServices($services);
        }
        
        $error = $config->get('error', false);
        if ($error) {
            ErrorHandler::register();
        }
        
        $this->app = new MvcApplication($this->di);
        $this->di->setShared('phalcon-app', $this->app);
        $this->app->setDI($this->di);
    }
    
    /**
     * Initialize the Services.
     *
     * @param  string[] $services
     * @return $this
     */
    protected function initializeServices(Config $services)
    {
        foreach ($services as $name => $configValue) {
            if ($configValue instanceof \Closure) {
                $this->di->setShared($name, $configValue);
            } else {
                if (is_string($configValue)) {
                    $name = $configValue;
                    $configValue = new Config([]);
                }
                
                if (isset($configValue['provider'])) {
                    $provider = new $configValue['provider']($name, $configValue, $this->di);
                } else {
                    $provider = $this->makeDefaultService($name, $configValue, $this->di);
                }
                
                $provider->register();
                $provider->boot();
            }
        }
        return $this;
    }

    /**
     * @param string $serviceName
     * @param Config $config
     * @param DiInterface $di
     * @return mixed
     */
    protected function makeDefaultService($serviceName, Config $config, DiInterface $di)
    {
        return DefaultServiceProviderFactory::make($serviceName, $config, $di);
    }
    
    /**
     * Runs the Application
     *
     * @return \Phalcon\Application|string
     */
    public function run()
    {
        return $this->getHandleContent();
    }
    
    /**
     * Handle application content.
     *
     * @return string
     */
    public function getHandleContent()
    {
        if ($this->app instanceof MvcApplication) {
            return $this->app->handle()->getContent();
        }
        return $this->app->handle();
    }
    
    /**
     * @return DiInterface
     */
    function getDI()
    {
        return $this->di;
    }

    /**
     * Get the Application.
     *
     * @return \Phalcon\Application|\Phalcon\Mvc\Micro
     */
    public function getApplication()
    {
        return $this->app;
    }
}

<?php

namespace phalconer\app\provider\basic;

use phalconer\common\provider\AbstractServiceProvider;
use Phalcon\Config;
use Phalcon\Mvc\Router;

class RouterServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'router';
    
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $config = $this->config;

        $this->di->setShared(
            $this->serviceName,
            function() use($config) {
                $router = new Router();
                $router->removeExtraSlashes(true);

                if (!empty($config['routes']) && $config['routes'] instanceof Config) {
                    foreach ($config['routes'] as $pattern => $params) {
                        call_user_func_array([$router, "add"], array_merge([$pattern], $params->toArray()));
                    }
                }

                $router->setDefaultNamespace($config['namespace']);
                return $router;
            }
        );
    }
}
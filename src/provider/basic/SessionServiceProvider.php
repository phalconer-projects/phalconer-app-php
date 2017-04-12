<?php

namespace phalconer\app\provider\basic;

use phalconer\common\provider\AbstractServiceProvider;

class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'session';
    
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
                $class = $config->get('class', \Phalcon\Session\Adapter\Files::class);
                $session = new $class();
                $session->start();

                return $session;
            }
        );
    }
}
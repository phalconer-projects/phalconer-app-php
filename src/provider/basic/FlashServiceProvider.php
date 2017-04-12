<?php

namespace phalconer\app\provider\basic;

use phalconer\common\provider\AbstractServiceProvider;

class FlashServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'flash';
    
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
                $class = $config->get('class', \Phalcon\Flash\Direct::class);
                $flash = new $class($config->get('styles', []));
                return $flash;
            }
        );
    }
}
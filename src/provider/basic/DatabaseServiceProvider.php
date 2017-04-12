<?php

namespace phalconer\app\provider\basic;

use phalconer\common\provider\AbstractServiceProvider;

class DatabaseServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'db';
    
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
                $adapter = ucfirst($config['driver']);
                $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

                $params = $config->toArray();
                unset($params['driver']);
                if ($adapter == 'Postgresql') {
                    unset($params['charset']);
                }

                $connection = new $class($params);

                return $connection;
            }
        );
    }
}
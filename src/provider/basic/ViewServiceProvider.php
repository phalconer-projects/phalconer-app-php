<?php

namespace phalconer\app\provider\basic;

use phalconer\common\provider\AbstractServiceProvider;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php;
use Phalcon\Mvc\View\Engine\Volt;

class ViewServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'view';
    
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
                $view = new View();
                $view->setDI($this);
                $view->setViewsDir($config['viewsDir']);

                $view->registerEngines([
                    '.volt' => function ($view) use($config) {
                        $volt = new Volt($view, $this);

                        $volt->setOptions([
                            'compiledPath' => $config['cacheDir'],
                            'compiledSeparator' => '_'
                        ]);

                        return $volt;
                    },
                    '.phtml' => Php::class,
                    '.php'   => Php::class
                ]);

                return $view;
            }
        );
    }
}
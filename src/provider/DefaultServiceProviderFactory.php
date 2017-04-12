<?php

namespace phalconer\app\provider;

use LogicException;
use Phalcon\DiInterface;
use Phalcon\Config;

class DefaultServiceProviderFactory
{
    public static $providers = [
        'crypt' => basic\CryptServiceProvider::class,
        'db' => basic\DatabaseServiceProvider::class,
        'flash' => basic\FlashServiceProvider::class,
        'modelsMetadata' => basic\ModelsMetadataServiceProvider::class,
        'router' => basic\RouterServiceProvider::class,
        'security' => basic\SecurityServiceProvider::class,
        'session' => basic\SessionServiceProvider::class,
        'url' => basic\UrlServiceProvider::class,
        'view' => basic\ViewServiceProvider::class
    ];
    
    public static function make($serviceName, Config $config, DiInterface $di)
    {
        $providerClass = self::$providers[$serviceName];
        if (!empty($providerClass)) {
            return new $providerClass($serviceName, $config, $di);
        } else {
            throw new LogicException(
                sprintf('The service provider class for name "%s" cannot found.', $serviceName)
            );
        }
    }
}

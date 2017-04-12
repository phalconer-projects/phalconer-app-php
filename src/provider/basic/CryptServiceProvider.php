<?php

namespace phalconer\app\provider\basic;

use Phalcon\Crypt;
use phalconer\common\provider\AbstractServiceProvider;

class CryptServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'crypt';
    
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $key = $this->config->get('key', NULL);
        if (empty($key)) {
            throw new \Phalcon\Crypt\Exception("Register service $this->serviceName: key cannot be empty");
        }

        $this->di->setShared(
            $this->serviceName,
            function() use($key) {
                $crypt = new Crypt();
                $crypt->setKey($key);

                return $crypt;
            }
        );
    }
}
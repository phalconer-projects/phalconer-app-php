<?php

namespace phalconer\app\provider\basic;

use phalconer\common\provider\AbstractServiceProvider;
use Phalcon\Mvc\Model\Metadata\Memory;

class ModelsMetadataServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName = 'modelsMetadata';
    
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function() {
                return new Memory();
            }
        );
    }
}
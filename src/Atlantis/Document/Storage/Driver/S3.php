<?php namespace Atlantis\Document\Storage\Driver;

use Codesleeve\Stapler\Stapler;
use Codesleeve\Stapler\Config\IlluminateConfig;

class S3 implements DriverInterface{
    protected $config;


    public function __construct($config){
        $this->config = $config;
    }


    public function boot(){
        //==============================================================================
        // Setup Stapler access
        //
        Stapler::boot();

        $this->config->addNamespace('stapler',__DIR__.'/../../../../config/stapler');
        $config = new IlluminateConfig($this->config, 'stapler');

        Stapler::setConfigInstance($config);

        if (!$config->get('stapler.public_path')) {
            $config->set('stapler.public_path', realpath(public_path()));
        }

        if (!$config->get('stapler.base_path')) {
            $config->set('stapler.base_path', realpath(base_path()));
        }
        //==============================================================================
    }

}
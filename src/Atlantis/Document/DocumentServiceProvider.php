<?php namespace Atlantis\Document;

use Illuminate\Support\ServiceProvider;
use Atlantis\Document\Storage\StorageManager;
use Atlantis\Document\Converter\ConverterManager;


class DocumentServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('atlantis/content');

        $this->bootServiceStorages();
        $this->bootServiceConverters();

        include __DIR__ . '/../../filters.php';
        include __DIR__ . '/../../routes.php';

        $this->app['events']->fire('atlantis.document.ready');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServiceStorage();
        $this->registerServiceConverter();
    }


    public function bootServiceStorages(){
        #i: Registering default storage drivers
        $this->app['document.storage']->register('s3', new \Atlantis\Document\Storage\Driver\S3($this->app['config']));

        #i: Booting storages
        $this->app['document.storage']->boot();
    }


    public function registerServiceStorage(){
        $this->app['document.storage'] = $this->app->share(function($app){
            return new StorageManager($app);
        });
    }


    public function registerServiceConverter(){
        $this->app['document.converter'] = $this->app->share(function($app){
            return new ConverterManager($app);
        });
    }


    public function bootServiceConverters(){
        #i: Registering PDF converter
        $this->app['document.converter']->register('pdf','snappy',new \Atlantis\Document\Converter\Pdf\Snappy($this->app));

        #i: Registering Image converter
        $this->app['document.converter']->register('image','snappy',new \Atlantis\Document\Converter\Image\Snappy($this->app));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('document.converter');
    }

}
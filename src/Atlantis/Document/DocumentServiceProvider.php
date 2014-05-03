<?php namespace Atlantis\Document;

use Illuminate\Support\ServiceProvider;


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
        #i: Default PDF Processor
        $this->app['document.pdf'] = $this->app->share(function($app)
        {
            #i: Get default PDF processor
            $pdf = $app['config']->get('content::document.default.pdf','zend');
            $pdf = studly_case($pdf);

            #i: Get processor instance
            $processor = $this->app->make('Atlantis\\Document\\Processor\Pdf\\'.$pdf);

            return $processor;
        });

        #i: Default Image Processor
        $this->app['document.image'] = $this->app->share(function($app)
        {
            #i: Get default PDF processor
            $image = $app['config']->get('content::document.default.image','snappy');
            $image = studly_case($image);

            #i: Get processor instance
            $processor = $this->app->make('Atlantis\\Document\\Processor\Image\\'.$image);

            return $processor;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('document.pdf', 'document.image');
    }

}
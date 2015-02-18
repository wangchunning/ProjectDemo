<?php namespace Tt\Entrust;

use Illuminate\Support\ServiceProvider;

class EntrustServiceProvider extends ServiceProvider {

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
        $this->package('wexchange/entrust');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('entrust', function($app)
        {
            return new Entrust($app);
        });
    }
}

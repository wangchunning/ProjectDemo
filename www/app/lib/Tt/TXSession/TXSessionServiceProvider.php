<?php namespace Tt\TXSession;

use Illuminate\Support\ServiceProvider;

class TXSessionServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                        
        $this->app['TXSession'] = $this->app->share(function($app)
        {
            return new TXSession;
        });
    }
}
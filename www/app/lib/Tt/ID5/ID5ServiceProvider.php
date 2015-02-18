<?php namespace Tt\ID5;

use Illuminate\Support\ServiceProvider;

class ID5ServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                        
        $this->app['ID5'] = $this->app->share(function($app)
        {
            return new ID5;
        });
    }
}
<?php namespace Tt\Ip2loc;

use Illuminate\Support\ServiceProvider;

class Ip2locServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                        
        $this->app['Ip2loc'] = $this->app->share(function($app)
        {
            return new Ip2loc;
        });
    }
}
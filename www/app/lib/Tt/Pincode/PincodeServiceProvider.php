<?php namespace Tt\Pincode;

use Illuminate\Support\ServiceProvider;

class PincodeServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                        
        $this->app['Pincode'] = $this->app->share(function($app)
        {
            return new Pincode;
        });
    }
}
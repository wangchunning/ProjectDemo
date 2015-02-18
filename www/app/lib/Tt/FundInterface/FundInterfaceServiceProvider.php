<?php namespace Tt\FundInterface;

use Illuminate\Support\ServiceProvider;

class FundInterfaceServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                       
        $this->app['FundInterface'] = $this->app->share(function($app)
        {
            return new FundInterface;
        });
    }
}
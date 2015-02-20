<?php namespace Lib\LoginService;

use Illuminate\Support\ServiceProvider;

class LoginServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                        
        $this->app['LoginService'] = $this->app->share(function($app)
        {
            return new LoginService;
        });
    }
}
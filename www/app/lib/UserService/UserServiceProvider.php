<?php namespace Lib\UserService;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider {

    /**
     * IoC binding of the service
     *
     * @return void
     */
    public function register()
    {                        
        $this->app['UserService'] = $this->app->share(function($app)
        {
            return new UserService;
        });
    }
}
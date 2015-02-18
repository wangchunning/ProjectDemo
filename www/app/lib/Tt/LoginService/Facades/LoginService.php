<?php namespace Tt\LoginService\Facades;

use Illuminate\Support\Facades\Facade;

class LoginService extends Facade {

    protected static function getFacadeAccessor() { return 'LoginService'; }
}
<?php namespace Tt\Email\Facades;

use Illuminate\Support\Facades\Facade;

class EmailProvider extends Facade {

    protected static function getFacadeAccessor() { return 'EmailProvider'; }
}
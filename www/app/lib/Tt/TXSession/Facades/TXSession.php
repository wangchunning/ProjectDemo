<?php namespace Tt\TXSession\Facades;

use Illuminate\Support\Facades\Facade;

class TXSession extends Facade {

    protected static function getFacadeAccessor() { return 'TXSession'; }
}
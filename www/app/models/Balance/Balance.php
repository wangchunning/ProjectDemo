<?php namespace Tt\Model;


class Balance extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_balance';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'uid';    
 

}
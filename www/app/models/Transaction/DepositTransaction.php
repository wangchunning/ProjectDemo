<?php namespace Tt\Model;


class DepositTransaction extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'deposit_transaction';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
    
    const TYPE_MANUAL	= 1;	// 后台手工充值
    const TYPE_AUTO		= 0;	// 用户自动充值
    
}
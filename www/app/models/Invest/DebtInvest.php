<?php namespace Tt\Model;


class DebtInvest extends \Eloquent {

    /**
     * Whether or not the primary key is auto incrementing?
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'debt_investor';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; 

    
    const SESSION_NAME		= '__DEBT_INVEST__';

}
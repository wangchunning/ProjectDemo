<?php namespace Tt\Model;



use DebtTx;
use DebtTxProfile;

class DebtTransactionProfile extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'debt_transaction_profile';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'tx_id'; 
    

}
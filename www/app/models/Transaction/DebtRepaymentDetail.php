<?php namespace Tt\Model;

use Config;


use Tx;
use DebtTx;
use DebtTxProfile;
use DebtTxMaterial;

class DebtRepaymentDetail extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'debt_repayment_detail';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
    
}
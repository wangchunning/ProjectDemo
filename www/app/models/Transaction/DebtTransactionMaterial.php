<?php namespace Tt\Model;


use DebtTx;
use DebtTxMaterial;

class DebtTransactionMaterial extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'debt_transaction_material';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';  
	
	const STATUS_PENDING	= 0;
	const STATUS_APPROVED	= 1;
	
	public static function status_desc($status)
	{
		switch ($status)
		{
			case DebtTxMaterial::STATUS_PENDING:
				return '待审核';
			
			case DebtTxMaterial::STATUS_APPROVED:
				return '审核通过';
		}
		
		return '';
	}
}
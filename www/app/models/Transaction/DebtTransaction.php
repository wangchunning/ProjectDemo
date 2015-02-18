<?php namespace Tt\Model;

use Config;


use Tx;
use DebtTx;
use DebtTxProfile;
use DebtTxMaterial;

class DebtTransaction extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'debt_transaction';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
    
    const SESSION_NAME		= '__DEBT_TX_';
    
    
    
    public $tx_profile;			// 填写资料 TxProfile
    public $tx_materials;		// 材料 TxMaterial
    
    
	public function tx_profile()
	{
		return DebtTxProfile::find($this->id);
	}

	public function tx_materials()
	{
		return DebtTxMaterial::where('tx_id', $this->id)->get();
	}
	
	public function approved_materials_unique_type()
	{
		$_ret		= array();
		
		$_materials	= $this->tx_materials();
		foreach ($_materials as $_material)
		{
			if ($_material->status != DebtTxMaterial::STATUS_APPROVED)
			{
				continue;
			}
			
			$_type	= Material::type_desc($_material->type);
			$_ret[$_type]	= $_material->op_date;
		}
		
		return $_ret;
	}
}
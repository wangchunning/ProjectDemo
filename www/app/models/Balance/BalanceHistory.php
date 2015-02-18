<?php namespace Tt\Model;


class BalanceHistory extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_balance_log';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
    
    const TYPE_FUND_AMOUNT		= 0;
    const TYPE_FUND_FEE			= 1;
    const TYPE_WITHDRAW_AMOUNT	= 2;
    const TYPE_WITHDRAW_FEE		= 3;
    const TYPE_WITHDRAW_CANCEL_REFUND	= 4;
    const TYPE_INVEST_AMOUNT	= 6;
    
    public function type_desc()
    {
    	switch ($this->type)
    	{
    		case BalanceHistory::TYPE_FUND_AMOUNT :
    			return '存款金额';
    		case BalanceHistory::TYPE_FUND_FEE :
    			return '存款手续费'; 
    		case BalanceHistory::TYPE_WITHDRAW_AMOUNT :
    			return '提现金额';
    		case BalanceHistory::TYPE_WITHDRAW_FEE :
    			return '提现手续费';
    		case BalanceHistory::TYPE_WITHDRAW_CANCEL_REFUND:
    			return '提现撤销返回费用';
    		case BalanceHistory::TYPE_INVEST_AMOUNT:
    			return '投资金额';
    	}
    	
    	return '';
    }
}
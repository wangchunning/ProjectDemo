<?php namespace Tt\Model;

use WithdrawTx;

class WithdrawTransaction extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'withdraw_transaction';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
    
    const SESSION_NAME = 'W_TX_SESSION';
    
    /**
     * status
     */
    const STATUS_PENDING	= 0;	// 待处理，default
    const STATUS_CONFIRMED	= 1;	// 已完成
    const STATUS_CANCELLED	= 2;	// 管理员打回
    const STATUS_PROCESSING	= 3;	// 处理中
    
    public static function status_arr()
    {
    	/**
    	 * array(id => desc)
    	 */
    	return array(
    			WithdrawTx::STATUS_PENDING 		=> '待处理',
    			WithdrawTx::STATUS_CONFIRMED 	=> '已完成',
    			WithdrawTx::STATUS_CANCELLED	=> '已驳回',
    	);
    }
    
    /**
     * 用于无法获得WithdrawTransaction对象的操作，如连表
     */
    public static function status_desc($status)
    {
    	$_status_arr = WithdrawTx::status_arr();

    	return isset($_status_arr[$status]) ? $_status_arr[$status] : '';
    }
    
    /**
     * bootstrap label
     * 	http://v3.bootcss.com/components/#labels
	 *
     */
    public static function status_style($status)
    {
    	switch ($status)
    	{
    		case WithdrawTx::STATUS_PENDING:
    			return 'success';
    	
    		case WithdrawTx::STATUS_CANCELLED:
    			return 'grey';
    	
    		//case WithdrawTx::STATUS_PENDING:
    		//	return  'success';
    	
    		case WithdrawTx::STATUS_CONFIRMED:
    			return 'default';
    	
    		default:
    			return 'grey';
    	}
    }
}
<?php namespace Tt\Model;

use AdminActivityLog;

class AdministratorActivityLog extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'administrator_activity_log';

    /**
     * 列说明：
     * op_category: 逻辑分类，对应后台管理的大模块
     * op_type: 对应具体的表
     * operation: 何种操作
     * obj_id	: 操作对象的id
     *  
     */

    const OP_CATEGORY_SETTING		= 1;
    	const OP_TYPE_ROLE		= 10;
    	const OP_TYPE_CREDIT	= 11;
    	
    const OP_CATEGORY_BORROW 		= 2;
    const OP_TYPE_DEBT_TX			= 20;
    
    const OP_CATEGORY_FINANCE 		= 3;
    	const OP_TYPE_WITHDRAW_TX	= 30;
    		
    const OP_CATEGORY_VERIFY 		= 4;
    
    const OP_CATEGORY_CUSTOMER 		= 5;
    
    const OP_CATEGORY_CONTENT 		= 6;
    
    const OP_CATEGORY_REPORT 		= 7;
    
	/**
	 * operation 对所有 optype, opcategory 唯一
	 * 唯一性用于 make_desc
	 */
    const OPERATION_WITHDRAW_TX_CLEAR	= 1;
    const OPERATION_WITHDRAW_TX_CANCEL	= 2;
    const OPERATION_DEBT_TX_FIRST_AUDIT_SUCC	= 3;
    const OPERATION_DEBT_TX_FIRST_AUDIT_FAIL	= 4;
    
    
    public static function category_desc($op_type)
    {
    	switch ($op_type)
    	{
    		case AdminActivityLog::OP_CATEGORY_SETTING:
    			return '系统设置';
    			
    		case AdminActivityLog::OP_CATEGORY_BORROW:
    			return '借款管理';
    		
    		case AdminActivityLog::OP_CATEGORY_FINANCE:
    			return '资金管理';
    			
    		case AdminActivityLog::OP_CATEGORY_VERIFY:
    			return '认证审核';
    			
    		case AdminActivityLog::OP_CATEGORY_CUSTOMER:
    			return '用户管理';
    							
    		case AdminActivityLog::OP_CATEGORY_CONTENT:
    			return '内容管理';
    								
    		case AdminActivityLog::OP_CATEGORY_REPORT:
    			return '统计报表';

    		default:
    			return '';
    											 
    	}
    	
    	return '';
    }
    
    public static function type_table($op_type)
    {
    	switch ($op_type)
    	{
    		case AdminActivityLog::OP_TYPE_ROLE:
    			return 'roles';
    			 
    		case AdminActivityLog::OP_TYPE_CREDIT:
    			return 'credit_level';
    
    		case AdminActivityLog::OP_TYPE_WITHDRAW_TX:
    			return 'withdraw_transaction';
    
    		case AdminActivityLog::OP_TYPE_DEBT_TX:
    			return 'debt_transaction';
    			
    		default:
    			return '';
    
    	}
    	 
    	return '';
    }

    public static function type_desc($op_type)
    {
    	switch ($op_type)
    	{
    		case AdminActivityLog::OP_TYPE_ROLE:
    			return '权限设置';
    
    		case AdminActivityLog::OP_TYPE_CREDIT:
    			return '信用积分';
    
    		case AdminActivityLog::OP_TYPE_WITHDRAW_TX:
    			return '提现';
    		
    		case AdminActivityLog::OP_TYPE_DEBT_TX:
    			return '抵押借款';
    			
    		default:
    			return '';
    
    	}
    
    	return '';
    }
    
    public static function operation_desc($operation)
    {
    	switch ($operation)
    	{
    		case AdminActivityLog::OPERATION_WITHDRAW_TX_CLEAR:
    			return '出账';
    
    		case AdminActivityLog::OPERATION_WITHDRAW_TX_CANCEL:
    			return '驳回请求';
    			
    		case AdminActivityLog::OPERATION_DEBT_TX_FIRST_AUDIT_SUCC:
    			return '初审通过';
    		
    		case AdminActivityLog::OPERATION_DEBT_TX_FIRST_AUDIT_FAIL:
    			return '初审驳回';  
    			  
    		default:
    			return '';
    
    	}
    
    	return '';
    }

	public static function make_desc($operation, $info_arr)
	{
		$_desc = '';
		
		switch ($operation)
		{
			case AdminActivityLog::OPERATION_WITHDRAW_TX_CLEAR:
			{
				$_desc = sprintf('对用户%s出账，%s', $info_arr['user_name'], 
									currencyFormat($info_arr['available_amount']));
				break;
			}
			case AdminActivityLog::OPERATION_WITHDRAW_TX_CANCEL:
			{
				$_desc = sprintf('对用户%s驳回提现请求，%s', $info_arr['user_name'], 
									currencyFormat($info_arr['available_amount']));
				break;
			}
			case AdminActivityLog::OPERATION_DEBT_TX_FIRST_AUDIT_SUCC:
			{
				$_desc = sprintf('对用户%s抵押贷初审通过，"%s"', $info_arr['user_name'],
							$info_arr['title']);
				break;
			}
			case AdminActivityLog::OPERATION_DEBT_TX_FIRST_AUDIT_FAIL:
			{
				$_desc = sprintf('对用户%s抵押贷初审驳回，"%s"', $info_arr['user_name'],
						$info_arr['title']);
				break;
			}
								
			default:
				return '';
		}
		
		return $_desc;
	}

}
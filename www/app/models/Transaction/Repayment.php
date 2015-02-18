<?php namespace Tt\Model;
use App;
use Config;
use Tx;
use DebtTx;

class Repayment {

    
    const TYPE_MONTH			= 0;	// 等额本息
    const TYPE_ALL_TOGETHER		= 1;	// 到期还本还息
    
    public static function desc($type)
    {	
    	switch ($type)
    	{
    		case Repayment::TYPE_MONTH:
    			return '等额本息，按月还款';
    			
    		case Repayment::TYPE_ALL_TOGETHER:
    			return '每月还息，到期还本还息';
    	}
    	
    	return '';   	
    }
    
    public static function detail($type)
    {	
    	switch ($type)
    	{
    		case Repayment::TYPE_MONTH:
    			return '等额本息还款法是在还款期内，每月偿还同等数额的贷款(包括本金和利息)。借款人每月还款额中的本金比重逐月递增、利息比重逐月递减';
    			
    		case Repayment::TYPE_ALL_TOGETHER:
    			return '';
    	}
    	
    	return '';   	
    } 

    /**
     * 计算借款人的应还款项
     * 
     * @param unknown $amount
     * @param unknown $period
     * @param unknown $rate
     * @param number $uid
     * @param unknown $product
     * @return multitype:NULL multitype:
     */
    
    public static function calc_repayment($amount, $period, $rate, $uid = 0, $product)
    {
    
    	$_amount	= $amount;
    	$_month		= $period;
    	$_rate		= $rate / 100;	// 借款年率
    	$_uid 		= $uid;
    	
    	$_tx_config = Tx::config($product);
    	
    	$_repayment_fee_rate = $_tx_config['repayment_rate'] / 100;
    	$_frozen_rate 		= $_tx_config['frozen_rate'] / 100;	// 冻结保证金比例
    	    
    	$_user		= User::find($_uid);
    	if (!empty($_user) && $_user->is_vip())
    	{
    		$_frozen_rate = $_tx_config['frozen_rate_vip'] / 100;	// vip冻结保证金比例
    	}
    	
    	$_month_rate		= $_rate / 12;
    	$_credit_config 	= CreditLevel::config();
    	$_service_fee_rate 	= 0;
    	if (!empty($_user))
    	{
    		$_service_fee_rate 	= $_credit_config[$_user->credit_level]['service_fee_rate'] / 100;	// 初期服务费比例
    	}
    
    	/*
    	 * 等额本息：
    	 * 	每月还本息总和 	= a*i(1+i)^N/[(1+i)^N-1]
    	 * 	第n个月还本金	= a*i(1+i)^(n-1)/[(1+i)^N-1]
    	 * 	第n个月还利息	= a*i(1+i)^N/[(1+i)^N-1]- a*i(1+i)^(n-1)/[(1+i)^N-1]
    	 * 
    	 * 	a=贷款总金额，i=贷款月利率，N=还贷总月数，n=第n期还贷数
    	 */
    	$_month_repay_to_investor	= $_amount * $_month_rate * pow(1 + $_month_rate, $_month) / (pow(1 + $_month_rate, $_month) - 1);
    	$_month_repay_fee			= $_amount * $_repayment_fee_rate;
    	$_month_repay_total_amount	= $_month_repay_to_investor + $_month_repay_fee;
    	$_service_fee				= $_amount * $_service_fee_rate;
    	$_frozen_amount				= $_amount * $_frozen_rate;
    
    	/**
    	 * 计算每月还款详情
    	 */
    	$_detail_arr = array();
    	$__unpaid_total_amount 		= $_month_repay_to_investor * $_month;
    	for ($_i = 0; $_i < $_month; $_i++)
    	{
    		// 还款第 N 期
    		$_detail_arr[$_i]['period_cnt']		= $_i + 1;	
    		// 本月应还款总额
    		$_detail_arr[$_i]['total_amount']	= round($_month_repay_total_amount, 2);	
    		// 本月应还本金
    		$__principal = $_amount * $_month_rate * pow(1 + $_month_rate, $_i) / (pow(1 + $_month_rate, $_month) - 1);
    		$_detail_arr[$_i]['principal_amount']	= round($__principal, 2); 
    		// 本月应还利息
    		$_detail_arr[$_i]['interest_amount'] 	= round($_month_repay_to_investor - $__principal, 2);
    		// 本月应还管理费
    		$_detail_arr[$_i]['fee_amount'] 		= round($_month_repay_fee, 2);
    		// 还剩多少没还
    		$__unpaid_total_amount -= $_month_repay_total_amount;
    		$_detail_arr[$_i]['unpaid_total_amount']	= round($__unpaid_total_amount, 2);
    	}
    	
    	$_ret	= array();
    	$_ret['repay_total']				= round($_month_repay_total_amount * $_month, 2);
    	$_ret['repay_principal']			= round($_amount, 2);
    	$_ret['month_repay_total']			= round($_month_repay_total_amount, 2);
    	$_ret['month_repay_to_investor']	= round($_month_repay_to_investor, 2);
    	$_ret['month_repay_fee']			= round($_month_repay_fee, 2);
    	$_ret['service_fee']				= round($_service_fee, 2);
    	$_ret['frozen_amount']				= round($_frozen_amount, 2);
    	$_ret['detail']						= $_detail_arr;
    	
    	return $_ret;
    }
    
    
    /**
     * 计算投资人的收益
     */ 
    public static function calc_benefit($amount, $period, $rate)
    {
    
    	$_amount	= $amount;		// 总投资额
    	$_month		= $period;		// 投资几个月
    	$_rate		= $rate / 100;	// 投资年率
	 
    	$_month_rate		= $_rate / 12;   
    	/*
    	 * 等额本息：
    	 * 	每月还本息总和 	= a*i(1+i)^N/[(1+i)^N-1]
    	 * 	第n个月还本金	= a*i(1+i)^(n-1)/[(1+i)^N-1]
    	 * 	第n个月还利息	= a*i(1+i)^N/[(1+i)^N-1]- a*i(1+i)^(n-1)/[(1+i)^N-1]
    	 *
    	 * 	a=贷款总金额，i=贷款月利率，N=还贷总月数，n=第n期还贷数
    	 */
    	$_month_total_amount	= $_amount * $_month_rate * pow(1 + $_month_rate, $_month) / (pow(1 + $_month_rate, $_month) - 1);
    	$_total_amount			= $_month_total_amount * $_month;
    	/**
    	 * 计算每月详情
    	 */
    	$_detail_arr = array();
    	$__unpaid_total_amount 		= $_total_amount;
    	for ($_i = 0; $_i < $_month; $_i++)
    	{
    		// 还款第 N 期
    		$_detail_arr[$_i]['period_cnt']		= $_i + 1;
    		// 本月应还款总额
    		$_detail_arr[$_i]['total_amount']	= round($_month_total_amount, 2);
    		// 本月应还本金
    		$__principal = $_amount * $_month_rate * pow(1 + $_month_rate, $_i) / (pow(1 + $_month_rate, $_month) - 1);
    		$_detail_arr[$_i]['principal_amount']	= round($__principal, 2);
    		// 本月应还利息
    		$_detail_arr[$_i]['interest_amount'] 	= round($_month_total_amount - $__principal, 2);
    		// 还剩多少没还
        	$__unpaid_total_amount -= $_month_total_amount;
        	$_detail_arr[$_i]['unpaid_total_amount']	= round($__unpaid_total_amount, 2);
    	}
        				 
        $_ret	= array();
        $_ret['principal']		= round($_amount, 2);
        $_ret['interest']		= round($_total_amount - $_amount, 2);
        $_ret['month_total']	= round($_month_total_amount, 2);
        $_ret['detail']			= $_detail_arr;
        				 
        return $_ret;
    }
    
}
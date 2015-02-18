<?php
/**
 *  用户余额相关辐助函数
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * 用户所有货币余额换算为 AUD 的总数
 * 
 *
 * @param  object  余额列表
 * @param  string  对应的汇率列表
 * @return float
 */
if ( ! function_exists('totalAUD'))
{
    function totalAUD($balances, $rates)
    {
        $output = 0;
        foreach ($balances as $key => $balance)
        {
            $output += $balance->available_amount * $rates[$key]['Bid'];
        }

        return currencyFormat('AUD', $output);
    }
}

/**
 * 用户的 AUD 余额
 * 
 *
 * @param  object  余额列表
 * @return float
 */
if ( ! function_exists('AUDBalance'))
{
    function AUDBalance($balances)
    {
        foreach ($balances as $balance)
        {
            if ($balance->currency == 'AUD')
            {
                return $balance->available_amount;
            }
        }

        return 0;
    }
}

/**
 * 当前用户的指定货币余额
 *
 * @param  string 
 * @param  bool   是否格式化金额
 * @param  int     用户uid 
 * @return float
 */
if ( ! function_exists('availableBalance'))
{
    function availableBalance($currency = 'AUD', $format = TRUE, $uid = NULL)
    {
        if ($uid) 
        {
            $user = User::find($uid);
        }
        else
        {
            $user = real_user();
        }
        $balance = $user->balances()->where('currency', '=', $currency)->first();

        $balance = isset($balance) ? $balance->available_amount : 0;

        return $format ? currencyFormat($currency, $balance) : $balance;
    }
}

/**
 *  返回余额账号
 *
 * @param  string  货币符号
 * @param  int     用户uid 
 * @return WeXchange\Model\Balance
 */
if ( ! function_exists('balanceAccount'))
{
    function balanceAccount($currency, $uid = NULL)
    {
        if ($uid) 
        {
            $user = User::find($uid);
        }
        else
        {
            $user = real_user();
        }
        $balance = $user->balances()->where('currency', '=', $currency)->first();

        // 不存在该货币账号，创建
        if ( ! $balance)
        {
            $balance = Balance::create(array(
                        'uid'           => $user->uid,
                        'currency'      => $currency,
                        'amount'        => 0,
                        'frozen_amount' => 0
            ));
        }

        return $balance;
    }
}

/**
 * 根据输入的 currency, amount 选出足够支付的账户
 *
 * @return Balance 对象数组
 */
if ( ! function_exists('payableUserBalance'))
{
    function payableUserBalance($amount, $currency = 'AUD')
    {
    	$ret_arr = array();

		$user = real_user();
        $user_type = $user->profile->vip_type;
		
		/*
		 * 选出用户的所有 Balance
		 */
        $balances = $user->balances()->get();
        if ($balances->isEmpty() || $amount == 0)
        {
        	return $balances;
        }
        
        /*
         * 优先选择同种货币的账户
         */
        foreach ($balances as $balance)
        {
        	$amount_from = $amount;
        	$currency_from = $currency;

        	$amount_to = 0;
        	$currency_to = $balance->currency;

			// 转换货币
	    	$rates = Exchange::query($currency_from.$currency_to, $user_type);  
	    	$amount_to = $amount * $rates[0]['Bid'];

        	if ($balance->available_amount >= $amount_to && 
                    $balance->available_amount != 0)
        	{
        		$ret_arr[] = $balance;
        	}
        }

        return $ret_arr;
        //$balance = isset($balance) ? $balance->available_amount : 0;

        //return $format ? currencyFormat($currency, $balance) : $balance;
    }
}

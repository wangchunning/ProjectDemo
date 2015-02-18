<?php
/**
 *  Transaction相关辐助函数
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * Transaction 操作状态检查
 * 
 *
 * @param  string id  transaction id
 * @param  string receipt_id
 * @return string
 */
if ( ! function_exists('action_check'))
{
    function action_check($id, $receipt_id = NULL)
    {
    	$detail = Transaction::find($id);

    	if ( ! $receipt_id) $receipt_id = $detail->receipt_id;

        $receipt = Receipt::find($receipt_id);

        $final = array('Completed', 'Canceled', 'Voided');

        // $transaction 已完成
        if (in_array($detail->status, $final)) return FALSE;

        $data = unserialize($receipt->data);

    	$action = TRUE;

    	switch ($detail->type) 
    	{
    		case 'FX Deal':
    			// 是否存在上一步操作
    			if (isset($data['deposit']))
    			{
    				$last_step = Transaction::where('receipt_id', '=', $receipt_id)->where('type', '=', 'Deposit')->first();
    				$action = $last_step->status == 'Completed' ? TRUE : FALSE;
    			}
    			break;

    		case 'Withdraw':
    			// 是否存在上一步操作
    			if (isset($data['deposit']))
    			{
    				$last_step = Transaction::where('receipt_id', '=', $receipt_id)->where('type', '=', 'Deposit')->first();
    				$action = $last_step->status == 'Completed' ? TRUE : FALSE;
    			}
                // 上一步没有未完成
                if ($action == FALSE) break;
                
    			if (isset($data['fx']))
    			{
    				$last_step = Transaction::where('receipt_id', '=', $receipt_id)->where('type', '=', 'FX Deal')->first();
    				$action = $last_step->status == 'Completed' ? TRUE : FALSE;
    			}
    			break;

    		default:
    			# code...
    			break;
    	}
    	return $action;
    }
}

/**
 * Transaction 操作权限检查
 * 
 *
 * @param  string $type   transaction type
 * @param  string $action 
 * @param  string $redirect  是否跳转到错误页 
 * @return bool | redirect
 */
if ( ! function_exists('trans_perm'))
{
    function trans_perm($type, $action = NULL, $redirect = TRUE)
    {
        $result = TRUE;

        switch ($type) 
        {
            case 'Deposit':
                if ( ! $action) 
                {
                    $result = check_perm('view_add_fund', FALSE);
                }
                else
                {
                    $result = check_perms('view_add_fund,' . $action . '_add_fund', 'AND', FALSE);
                }                
                break;

            case 'FX Deal':
                if ( ! $action) 
                {
                    $result = check_perm('view_fx', FALSE);
                }
                else
                {
                    $result = check_perms('view_fx,' . $action . '_fx', 'AND', FALSE);
                }
                break;

            case 'Withdraw':
                if ( ! $action) 
                {
                    $result = check_perm('view_withdraw', FALSE);
                }
                else
                {
                    $result = check_perms('view_withdraw,' . $action . '_withdraw', 'AND', FALSE);
                }
                break;
            
            default:
                # code...
                break;
        }

        if ( ! $redirect) 
        {
            return $result;
        }

        if ( ! $result) 
        {
            // 跳转到没有权限的错误页面
            App::error(function($exception)
            {
                return Response::view('errors.unauthorize');
            });
            return App::abort('401');
        }
        return TRUE;
    }
}

/**
 * 生成Transaction detail url 
 * 
 *
 * @param  string id  transaction id
 * @return string
 */
if ( ! function_exists('transaction_detail_url'))
{
    function transaction_detail_url($id)
    {
        return sprintf('<a class="underline" href="/admin/transaction/detail/%s">%s</a>', $id, $id);
    }
}

/**
 * 生成Receipt detail url 
 * 
 *
 * @param  string id  transaction id
 * @return string
 */
if ( ! function_exists('receipt_detail_url'))
{
    function receipt_detail_url($id)
    {
        return sprintf('<a class="underline" href="/admin/transaction/receipt/%s">%s</a>', $id, $id);
    }
}

/**
 * 返回 Transaction Status 的样式名称
 *
 * @param  string
 * @return string
 */
if ( ! function_exists('transactionStatusStyle'))
{
    function transactionStatusStyle($status = '')
    {
        switch ($status)
        {
            case 'Waiting for fund':
                $style = 'warning';
                break;

            case 'Overdue':
                $style = 'danger';
                break;

            case 'Pending':
                $style = 'success';
                break;

            case 'Completed':
                $style = 'default';
                break;

            default:
                $style = 'grey';
                break;
        }

        return $style;
    }
}

/**
 * 检查 Transaction 是否超时
 *
 * @param  string
 * @param  int $day
 * @return string
 */
if ( ! function_exists('overdueCheck'))
{
    function overdueCheck($created_at, $day = 2)
    {
        $now = time();

        $from = strtotime((string)$created_at);

        // Distance in seconds
        $s = round(abs($now - $from));
        // Distance in minutes
        $m = round($s / 60);

        if ( $m > 1440 * $day )
        {
            return sprintf('Overdue %s days', ceil(floatval($m - (1440 * $day)) / 1440));
        }

        return FALSE;
    }
}

/**
 * 自动获取withdraw的银行信息
 *
 * @param array transaction data 
 * @return array
 */
if ( ! function_exists('autoWithdrawBank'))
{
    function autoWithdrawBank(&$data)
    {
        $currency = $data['currency'];
        $country = $data['recipient']['country'] ? $data['recipient']['country'] : $data['recipient']['transfer_country'];
        $amount = $data['amount'];

        // 优先选择与汇款国家一致，且为Westpac的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('bank', '=', 'Westpac')
                    ->where('country', $country)
                    ->get();
        if ($bankinfo = withdraw_bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }


        // 选择与汇款国家一致的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('country', $country)
                    ->get();
        if ($bankinfo = withdraw_bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        // 没有与汇款国家一致的银行账号？优先选择澳洲的Westpac
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('bank', '=', 'Westpac')
                    ->where('country', 'Australia')
                    ->get();
        if ($bankinfo = withdraw_bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        // 优先选择澳洲的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('country', 'Australia')
                    ->get();
        if ($bankinfo = withdraw_bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        // 选择支持汇入货币的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->get();
        if ($bankinfo = withdraw_bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        return FALSE;
    }
}

/**
 * 判断最适合的银行
 *
 * @param object
 * @param float 
 * @param string $currency
 * @return array
 */
if ( ! function_exists('withdraw_bank_match'))
{
    function withdraw_bank_match(&$banks, $amount, $currency)
    {
        // 备选银行
        $bankinfo = array();
        // 银行余额
        $bankbalance = array();
        // 选择的银行
        $select = array();

        foreach ($banks as $bank) 
        {
            // 对应银行余额
            $bankbalance[$bank->id] = $bank->getAmount($currency);

            // 无限额
            if ( ! $bank->limit) 
            {
                $bankinfo[$bank->id] = 0;               
                continue;
            }
            $bankinfo[$bank->id] = $bank->limit;
        }

        // 目前银行限额只针对CNY
        if ($currency != 'CNY') 
        {
            // 检测余额是否足够
            foreach ($bankinfo as $k => $val) 
            {
                if (withdraw_balance_handle($bankbalance[$k], $amount)) 
                {
                    $select[] = array('id' => $k, 'amount' => $amount);
                    return $select;
                }
            }
            return FALSE;            
        }

        // 符合条件的银行限额总和大于存款金额
        if (array_sum($bankinfo) > $amount) 
        {
            arsort($bankinfo, SORT_NUMERIC);
            $select = withdraw_amount_handle($bankinfo, $amount, $bankbalance, $select);

            return $select;
        }

        // 是否存在无限额的银行账号
        foreach ($bankinfo as $k => $val) 
        {
            if ($val == 0 AND withdraw_balance_handle($bankbalance[$k], $amount)) 
            {
                $select[] = array('id' => $k, 'amount' => $amount);
                return $select;
            }
        }

        return FALSE;
    }
}

/**
 * 递归处理限额与金额，匹配最佳银行信息
 *
 * @param array
 * @param float 
 * @param array
 * @param array
 * @return array
 */
if ( ! function_exists('withdraw_amount_handle'))
{
    function withdraw_amount_handle($bankinfo, $amount, $bankbalance, $select)
    {
        // 限额最大值小于存款金额
        $max_limit = reset($bankinfo);
        if ($max_limit < $amount) 
        {
            // 是否存在无限额的银行账号
            foreach ($bankinfo as $k => $val) 
            {
                if ($val == 0 AND withdraw_balance_handle($bankbalance[$k], $amount)) 
                {
                    $select[] = array('id' => $k, 'amount' => $amount);
                    return $select;
                }
            }            

            //拆分银行存款
            reset($bankinfo);
            $bank_id = key($bankinfo);

            // balance是否足够
            if (withdraw_balance_handle($bankbalance[$bank_id], $max_limit)) 
            {
                $select[] = array('id' => $bank_id, 'amount' => $max_limit);
                $amount = $amount - $max_limit;
            }

            unset($bankinfo[$bank_id]);
            return withdraw_amount_handle($bankinfo, $amount, $bankbalance, $select);
        }

        // 差值
        $d_value = array();
        foreach ($bankinfo as $k => $limit) 
        {
            // 优先选择有限额的银行账号
            $val = $limit - $amount;
            if ($val >= 0) 
            {
                $d_value[$k] = $val;
            }       
        }
        // 取最小差值
        asort($d_value, SORT_NUMERIC);
        $d_key = key($d_value);

        // balance是否足够   
        if (withdraw_balance_handle($bankbalance[$d_key], $amount)) 
        {
            $select[] = array('id' => $d_key, 'amount' => $amount);
            return $select;
        }

        // balance不足以支付
        unset($bankinfo[$d_key]);

        if ($bankinfo) 
        {
            return withdraw_amount_handle($bankinfo, $amount, $bankbalance, $select);
        }
        return FALSE;
    }
}

/**
 * 比较银行余额与汇款金额
 *
 * @param float $balance
 * @param float 
 * @return bool
 */
if ( ! function_exists('withdraw_balance_handle'))
{
    function withdraw_balance_handle($balance, $amount)
    {
        if ($balance > $amount) 
        {
            return TRUE;
        }
        return FALSE;
    }
}


<?php
/**
 *  Receipt 相关辅助函数
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * 生成 transaction 流水号
 *
 *
 * @param  string
 * @return string
 */

function tn($type = 'D')
{
	Carbon::setToStringFormat('Ymd');
	
	return sprintf('%s%s%05d', 
						$type, 
						Carbon::now()->__toString(),
						rand(0, 99999)
				);

}


/**
 * 生成 receipt 流水号
 *
 * @param  string
 * @return string
 */
if ( ! function_exists('rn'))
{
    function rn($type = 'D')
    {
        $file = 'uploads/r.dat';

        if ($id = @file_get_contents($file))
        {
            $id++;
        }
        else
        {
            $id = Date('Ymd', Carbon::today()->timestamp) . '0001';
        }
        
        $fp = fopen($file, 'w+');

        flock($fp, LOCK_EX);

        fwrite($fp, $id);

        flock($fp, LOCK_UN);

        return sprintf('R%s%s', $id, $type);        
    }
}

/**
 * 返回 Receipt Status 的样式名称
 *
 * @param  string
 * @return string
 */
if ( ! function_exists('receiptStatusStyle'))
{
    function receiptStatusStyle($status = '')
    {
        switch ($status)
        {
            case 'Waiting for fund':
                $style = 'warning';
                break;

            case 'Processing':
                $style = 'success';
                break;

            case 'Completed':
                $style = 'default';
                break;

            default:
                $style = 'default';
                break;
        }

        return $style;
    }
}

/**
 * 指定货币的银行信息
 *
 * @param int $bank_id
 * @param string
 * @param float
 * @return string
 */
if ( ! function_exists('bankInfo'))
{
    function bankInfo($bank_id, $currency = '', $amount = 0)
    {

        $bank = Bank::find($bank_id);
        if ( ! $bank)
        {
            return '<div class="mt20 f18p">您的订单已提交成功，关于收款行信息，我们会尽快联系您，请您耐心等待</div>';
        }

        if ( ! $currency) 
        {
            $currency = explode('|', $this->currency);
            $currency = $currency[0];
        }

        /**
         * 如果收款行是 westpac, 则收款行是 westpac 用户自己的账号
         * 否则是 wexchange 的账号
         */
        if(stristr($bank->bank, 'Westpac') === FALSE)
        {
            $str = sprintf('<div class="bank-info"><h5><strong>%s%s</strong>%s %s</h5>', 
                            $bank->bank, 
                            $bank->branch ? ' - ' . $bank->branch . ' (分行)' : '', 
                            $bank->swift_code ? ' <span class="color-999">(SWIFT:' . $bank->swift_code . ')</span>' : '',
                            currencyFormat($currency, $amount, TRUE) 
                            );
            $str .= sprintf('<p class="account-name"><span class="color-999">账户名</span><span>%s</span></p>', $bank->account);
            if ($bank->BSB)
            {
                $str .= sprintf('<p class="account-number"><span class="color-999">BSB & 银行账号</span><span>%s%s</span><img class="currency ml10" src="%s.png" alt="%s" title="%s"></p>', '<span class="mr10">' . $bank->BSB . '</span>', $bank->account_number, URL::asset('assets/img/flags/' . $currency), $currency, $currency);
            }
            else
            {
                 $str .= sprintf('<p class="account-number"><span class="color-999">银行账号</span><span>%s</span><img class="currency ml10" src="%s.png" alt="%s" title="%s"></p>', $bank->account_number, URL::asset('assets/img/flags/' . $currency), $currency, $currency);
            }
            $str .= '</div>';

            return $str;
        }

        $user = real_user();
        $an = $user->account_number;
        $str = sprintf('<div class="bank-info"><h5><strong>%s%s</strong>%s %s</h5>', 
                            $bank->bank, 
                            $bank->branch ? ' - ' . $bank->branch . ' (分行)' : '', 
                            $bank->swift_code ? ' <span class="color-999">(SWIFT:' . $bank->swift_code . ')</span>' : '',
                            currencyFormat($currency, $amount, TRUE) 
                            );
        $str .= sprintf('<p class="account-name"><span class="color-999">账户名</span><span>%s</span></p>', $bank->account);
        if ($bank->BSB)
        {
            $str .= sprintf('<p class="account-number"><span class="color-999">BSB & 银行账号</span><span>%s%s</span><img class="currency ml10" src="%s.png" alt="%s" title="%s"></p>', '<span class="mr10">' . $bank->BSB . '</span>', $currency == 'AUD' ? $an : $bank->account_number, URL::asset('assets/img/flags/' . $currency), $currency, $currency);   
        }
        else
        {
            $str .= sprintf('<p class="account-number"><span class="color-999">银行账号</span><span>%s</span><img class="currency ml10" src="%s.png" alt="%s" title="%s"></p>', $currency == 'AUD' ? $an : $bank->account_number, URL::asset('assets/img/flags/' . $currency), $currency, $currency);   
        }
        $str .= '</div>';
       
        return $str;
    }
}

/**
 * 自动获取指定货币的银行信息
 *
 * @param string
 * @param float 
 * @return array
 */
if ( ! function_exists('autoSelectBank'))
{
    function autoSelectBank($currency = 'AUD', $amount = 0)
    {
        $user = real_user();
        $reg_country = $user->reg_country ? $user->reg_country : 'Australia';

        // 优先选择与用户注册国家一致，且为Westpac的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('bank', 'like', '%Westpac%')
                    ->where('country', $reg_country)
                    ->where('status', 1)
                    ->get();
        if ($bankinfo = bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }


        // 选择与用户注册国家一致的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('country', $reg_country)
                    ->where('status', 1)
                    ->get();
        if ($bankinfo = bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        // 没有与用户注册国家一致的银行账号？优先选择澳洲的Westpac
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('bank', 'like', '%Westpac%')
                    ->where('country', 'Australia')
                    ->where('status', 1)
                    ->get();
        if ($bankinfo = bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        // 优先选择澳洲的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('country', 'Australia')
                    ->where('status', 1)
                    ->get();
        if ($bankinfo = bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        // 选择支持汇入货币的银行
        $bank = Bank::where('currency', 'like', '%'. $currency .'%')
                    ->where('status', 1)
                    ->get();
        if ($bankinfo = bank_match($bank, $amount, $currency)) 
        {
            return $bankinfo;
        }

        return array();
    }
}

/**
 * 判断最适合的银行
 *
 * @param object
 * @param float 
 * @param string $currency
 * @return array [bank_id]
 */
if ( ! function_exists('bank_match'))
{
    function bank_match($banks, $amount, $currency)
    {
        $UNLIMITED     = 99999999999999;
        $nearest_limit      = $UNLIMITED;
        $ret_arr            = array();
        $bank_arr_name_key  = array();

        if ($banks->isEmpty())
        {
            return false;
        }

        /**
         * 找出最接近的 limit
         */
        foreach ($banks as $bank) 
        {
            if (!$bank->limit || $bank->limit < $amount)
            {
                continue;
            }
 
            if ($bank->limit - $amount < $nearest_limit - $amount)
            {
                $nearest_limit = $bank->limit; 
            }          
        }

        /**
         * 遍历所有备选银行，找出 limit 向下最接近的银行，并去重，
         * 结果按照 balance 降序排列
         */
        $balance_arr = array();
        foreach ($banks as $bank) 
        {
            $bank_name = $bank->bank;
            // 有无限额，无限额 limit = 0
            $limit = $bank->limit ? $bank->limit : $UNLIMITED; 

            /**
             * 如果已经有同名银行，则比较余额，取余额少的, 且
             * 取最接近 limit 的
             */
            $bank_balance = $bank->getAmount($currency);
            if ((array_key_exists($bank_name, $bank_arr_name_key) &&
                    $bank_balance > $bank_arr_name_key[$bank_name]['balance']) || 
                $limit != $nearest_limit) 
            {
                continue;
            } 

            $bank_arr_name_key[$bank_name] = 
                                array(
                                        'id'    => $bank->id,
                                        'limit' => $limit,
                                        'balance' => $bank_balance,
                                        );

        }

        foreach ($bank_arr_name_key as $b_arr)
        {
            $balance_arr[] = $b_arr['balance'];
        }
        
        array_multisort($balance_arr, SORT_ASC, $bank_arr_name_key);

        /**
         * make result
         */
        foreach ($bank_arr_name_key as $b_name => $b_arr)
        {
            $ret_arr[] = $b_arr['id'];
        }

        return $ret_arr;

    }
}

/**
 * 递归处理限额与金额，匹配最佳银行信息
 *
 * @param array
 * @param float 
 * @param array
 * @return array
 */
if ( ! function_exists('amount_handle'))
{
    function amount_handle($bankinfo, $amount, $select)
    {
        // 限额最大值小于存款金额
        $max_limit = reset($bankinfo);
        if ($max_limit < $amount) 
        {
            // 是否存在无限额的银行账号
            if ($bank_id = array_search(0, $bankinfo)) 
            {
                $select[] = array('id' => $bank_id, 'amount' => $amount);
                return $select;
            }

            //拆分银行存款
            $amount = $amount - $max_limit;
            $bank_id = key($bankinfo);

            $select[] = array('id' => $bank_id, 'amount' => $max_limit);
            unset($bankinfo[$bank_id]);

            return amount_handle($bankinfo, $amount, $select);
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
                $select[] = array('id' => $k);  
            }     

        }
        // 取最小差值
        //asort($d_value, SORT_NUMERIC);
        //$select[] = array('id' => key($d_value), 'amount' => $amount);

        return $select;
    }
}

/**
 * 返回 Receipt Status 的提示信息
 *
 * @param  receipt id
 * @return string
 */
if ( ! function_exists('receiptTips'))
{
    function receiptTips($id = '')
    {
        if ($receipt = Receipt::find($id)) 
        {
            switch ($receipt->status)
            {
                case 'Waiting for fund':
                    $style = 'info';
                    $text = '您的交易已提交成功。请您在 48 小时之内转账至 Anying 银行账号';
                    break;

                case 'Uncompleted':
                    $style = 'warning';
                    $text = '很抱歉，我们不能完成您的交易请求。如果您有任何疑问，请发邮件给我们 <a href="mailto:info@anying.com">info@anying.com</a>';
                    break;

                case 'Completed':
                    $style = 'success';
                    $text = '您的交易已完成！<a href="/home">再做一笔</a>?';
                    break;

                case 'Voided':
                    $style = 'warning';
                    $text = '很抱歉，我们不能完成您的交易请求。如果您有任何疑问，请发邮件给我们 <a href="mailto:info@anying.com">info@anying.com</a>';
                    break;

                default:
                    $style = 'info';
                    $text = '您的交易已完成！';
                    break;
            }

            $tran = $receipt->transaction('Deposit');
            if ($tran AND $receipt->status != 'Completed') 
            {
                switch ($tran->status) 
                {
                    case 'Waiting for fund':
                        $style = 'info';
                        $text = '您的交易已提交成功。请您在 48 小时之内转账至 Anying 银行账号';
                        break;

                    // 已完成add fund
                    case 'Completed':
                        $style = 'info';
                        $text = '您的交易已完成！';
                        break;
                    
                    case 'Voided':
                        $style = 'warning';
                        $text = '很抱歉，我们无法完成您的交易。如果您有任何疑问，请发邮件给我们 <a href="mailto:info@anying.com">info@anying.com</a>';
                        break;

                    default:
                        $style = 'info';
                        $text = '您的交易已提交成功，我们正等待着您的付款';
                        break;
                }
                
                // 用户over due
                if ($tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                {
                    $style = 'danger';
                    $text = '很抱歉，您的交易已经超时，请您务必在 24 小时内转账至 Anying 银行账户';
                }
            }
return <<<EOD
<div class="alert alert-{$style} alert-dismissable noprint">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{$text}</strong>
</div> 
EOD;
        }
    }
}

/**
 * ReceiptAmountInOut 
 *     计算 array(receipt) 的 in/out 和
 * @param  array(Receipt)
 * @return array('in' => XXX, 'out' => XXX)
 * 
 */
if ( ! function_exists('receiptAmountInOut'))
{
    function receiptAmountInOut($receipts, $currency_to = 'AUD')
    {
    	$ret_arr = array();
    	$ret_arr['in'] = 0;
    	$ret_arr['out'] = 0;

    	foreach($receipts as $receipt)
    	{
    		$in = 0;
    		$out = 0;
    		$currency_in = "";
    		$currency_out = "";
    		$data = unserialize($receipt->data);

            $amount_arr = $receipt->amount_total;
    		switch ($receipt->type)
    		{
    		case 'Deposit':
    			$currency_in = $amount_arr['currency'];
    			$in = $amount_arr['amount'];
    			break;

    		case 'Withdraw':
    			$out = $amount_arr['amount'];
    			$currency_out = $amount_arr['currency'];
    			break;

    		case 'FX Deal':
    		default:
    			$in  = $amount_arr['amount'];
    			$currency_in = $amount_arr['currency'];

    			$out = $amount_arr['amount'] * $receipt->fx_rate;
    			$currency_out = $receipt->fx_currency_to;    		
    			break;
    		}	

    		if ($in != 0)
    		{
	    		$rates = Exchange::query($currency_in.$currency_to, 'manager');  
	    		$ret_arr['in'] += $in * $rates[0]['Bid'];
    		}
    		if ($out != 0)
    		{
                $converted_fee_arr = $receipt->converted_fee_total;
                $rates = Exchange::query($converted_fee_arr['convert_fee_currency'].$currency_to, 'manager');   
                $ret_arr['out'] += $converted_fee_arr['convert_fee_amount'] * $rates[0]['Bid']; 
                           
	    		$rates = Exchange::query($currency_out.$currency_to, 'manager');  
	    		$ret_arr['out'] += $out * $rates[0]['Bid'];
    		}

    	}


    	return $ret_arr;

    }
}


/**
 * 返回 Receipt Type 的样式名称
 *
 * @param  string
 * @return string
 */
if ( ! function_exists('receiptTypeString'))
{
    function receiptTypeString($receipt_id)
    {
        $type = array();
        if (empty($receipt_id))
        {
            return '';
        }

        $pos = strpos($receipt_id, 'W');
        if ($pos != false) 
        {
            $type[] = 'Withdraw';
        }
                            
        $pos = strpos($receipt_id, 'D');
        if ($pos != false) 
        {
            $type[] = 'Deposit';
        }
                                               
        $pos = strpos($receipt_id, 'F');
        if ($pos != false) 
        {
            $type[] = 'FX';
        } 

        return implode($type, ', ');
    }
}

/**
 * receipt overdue date
 *     receipt_created_at + 2 days
 *      
 * @param  string  $string_date
 * @return string
 */
if ( ! function_exists('receipt_overdue_date'))
{
    function receipt_overdue_date($string_date)
    {
        $overdue_days = "2";
        $receipt_overdue_date_gmt = date("Y-m-d H:i", 
                                        strtotime("+".$overdue_days." days",
                                                 strtotime($string_date)));

        return substr(time_to_tz($receipt_overdue_date_gmt), 0, -6);

    }
}

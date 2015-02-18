<?php

/*
|--------------------------------------------------------------------------
| Application Custom Validators
|--------------------------------------------------------------------------
|
| Here is where you can find the custom validators for form validation.
|
*/

/*
|--------------------------------------------------------------------------
| 验证用户登录失败次数超过5次将会限制一小时后登录
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('limit_login', function($attr, $value) {
    
	$_limits 		= User::LOGIN_LIMIT_CNT;
	$_limit_hour 	= User::LOGIN_LIMIT_HOUR;	// 1 小时
	
	$_login_ip 	= Request::getClientIp();
	$_login_cnt = 0;
	if (Request::is('admin/*'))
	{
		$_login_cnt = AdminLoginLog::where('login_ip', $_login_ip)
						->whereBetween('created_at', array(Carbon::now()->subHours($_limit_hour), Carbon::now()))
                       	->count();
	}
	else 
	{
		$_login_cnt = UserLoginLog::where('login_ip', $_login_ip)
						->whereBetween('created_at', array(Carbon::now()->subHours($_limit_hour), Carbon::now()))
						->count();		
	}

	return ($_login_cnt <= $_limits);
    
});

/*
|--------------------------------------------------------------------------
| 验证是否澳洲手机号码
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('aumobile', function($attr, $value) {
    return preg_match('/^(0{0,1}4[0-9]{2})([0-9]{3})([0-9]{3})$/', $value);
});

/*
|--------------------------------------------------------------------------
| 验证是否有效的电话号码
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('valid_tel', function($attr, $value) {
    return preg_match('/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/i', $value);
});

/*
|--------------------------------------------------------------------------
| 验证 Australian Business Number (ABN)
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('abn', function($attr, $value) {
    $value = str_replace('-', '', $value);
    $value = str_replace(' ', '', $value);

    $abn = ABNLookup::searchByAbn($value);

    if ($abn->status == 'Active')
    {
        return TRUE;
    }

    return FALSE;
});
/*
|--------------------------------------------------------------------------
| 验证指定的收款人 ID 是否属于当前用户
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('valid_recipient', function($attr, $value) {

    if ( ! is_numeric($value))
    {
        return FALSE;
    }

    $account = RecipientBank::find($value);

    $user = real_user();
    return $user->recipients->find($account->recipient_id) ? TRUE : FALSE;
    
});

/*
|--------------------------------------------------------------------------
| Withdraw 时，验证当前用户的帐户余额是否足够支付指定的金额
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('amount_transfer', function($attr, $value) {

    
    // 手续费
    $fee = Receipt::accountFee(Input::get('currency'));
    $fee = $fee['total'];

    $balance = availableBalance(Input::get('currency'), FALSE);

    if ($balance < (Input::get('amount') + $fee))
    {
        return FALSE;
    }

    return TRUE;
});
/*
|--------------------------------------------------------------------------
| 验证是否成功输入了 SMS PIN
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('valid_pin', function($attr, $value) {
    
    //return Pincode::validate(Input::get('sms_pin'));
    return true;
});

/*
|--------------------------------------------------------------------------
| 验证是否需要输入 Account BSB
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('account_bsb', function($attr, $value) {

    if (Input::get('transfer_to') != 'new')
    {
        return TRUE;
    }

    if ( ! in_array(Input::get('country'), array('Australia', 'United Kingdom')))
    {
        return TRUE;
    }

    if ($value)
    {
        return TRUE;
    }

    return FALSE;
});

/*
|--------------------------------------------------------------------------
| 后台银行管理验证是否需要输入 Account BSB
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('bank_account_bsb', function($attr, $value) {

    if ( ! in_array(Input::get('country'), array('Australia', 'United Kingdom')))
    {
        return TRUE;
    }

    if ($value)
    {
        return TRUE;
    }

    return FALSE;
});

/*
|--------------------------------------------------------------------------
| 验证货币是否被银行支持
|--------------------------------------------------------------------------
| 主要用于 Lock Rate 中检查用户选择的货币是否有银行支持 Deposit，不可以的话则只能用余额操作
|
*/
Validator::extend('support_for_deposit', function($attr, $value) {

    // 该 Receipt 的支付信息
    $payment = Receipt::payment();

    // 支持货币的银行
    $bank = new Bank;   
    $bank = $bank->getFundCurrencies();

    // 没有银行支持时且支付方式为 Depoist
    if (empty($bank) AND Input::get('payment_method') == 'deposit')
    {
        return FALSE;
    }

    // 没有银行支持且不能用余额支付?
    if (empty($bank) AND $payment['pay'] > 0)
    {
        return FALSE;
    }

    return TRUE;
});

/*
|--------------------------------------------------------------------------
| 验证是否有效国家
|--------------------------------------------------------------------------
|
*/
Validator::extend('valid_country', function($attr, $value) {

        $country = ucfirst($value);

        $countries = countries();

        return isset($countries[$country]);  
});

/*
|--------------------------------------------------------------------------
| 验证是否是money
|--------------------------------------------------------------------------
|
|
*/
Validator::extend('money_amount', function($attr, $value) {
    if (!is_numeric($value) || $value <= 0)
    {
        return false;
    }
    return preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value);
});

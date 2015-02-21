<?php

/*
|--------------------------------------------------------------------------
| Application Custom Validators
|--------------------------------------------------------------------------
|
| Laravel 表单验证规则扩展，用于 controller
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
*/
Validator::extend('money_amount', function($attr, $value) {
    if (!is_numeric($value) || $value <= 0)
    {
        return false;
    }
    return preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value);
});

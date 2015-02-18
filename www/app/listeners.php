<?php

use Carbon\Carbon;
//use WeXchange\Model\LoginLogs;
use Tt\Model\User;
use Illuminate\Support\Facades\Request;
/*
|--------------------------------------------------------------------------
| Application Event Listeners
|--------------------------------------------------------------------------
|
| Here is where you can find the event handlers/listeners all across the 
| application.
|
*/

/*
|--------------------------------------------------------------------------
| Login Event Listeners
|--------------------------------------------------------------------------
|
| Update the user login metadata and more...
|
*/
/*Event::listen('user.login', function($user)
{   
    $user->save_access_log();
});*/

// 用户登录失败记录
/*Event::listen('user.loginFailed', function($user_name)
{
	$_login_ip 	= Request::getClientIp();
	
	$_log = new UserLoginLog;
	// 后台系统
	if (Request::is('admin/*'))
	{
		$_log = new AdminLoginLog;
	}

	// 封ip
	$_log->login_ip		= $_login_ip;
	$_log->user_name	= $user_name;
	
	$_log->save();

});*/

/*
|--------------------------------------------------------------------------
| Model Observer Listeners
|--------------------------------------------------------------------------
|
|
*/
//App::make('WeXchange\Model\Profile')->observe(new WeXchange\Model\ProfileObserver);
//App::make('WeXchange\Model\Verification')->observe(new WeXchange\Model\VerificationObserver);
//App::make('WeXchange\Model\Receipt')->observe(new WeXchange\Model\ReceiptObserver);
//App::make('Tt\Model\User')->observe(new Tt\Model\UserObserver);
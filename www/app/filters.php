<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if ( ! is_login()) 
	{
		// 用于 ajax 判定是否登录
		if (Request::ajax())
    	{
        	return Response::json(array('status' => 'log out'));
    	}
    	return Redirect::guest('login');
	}

	$_user = login_user();
    // 账户在别处登录？退出当前会话
    if (Session::getId() != $_user->login_session)
    {
        Auth::client()->logout();

        return Redirect::to('login')->with('error', '您的账号已在别处登录，如果不是您本人操作，请及时修改密码');        
    }
});

Route::filter('auth.admin', function()
{
	if ( ! is_login(Administrator::LABEL))
	{
		// 用于 ajax 判定是否登录
		if (Request::ajax())
    	{
        	return Response::json(array('status' => 'log out'));
    	}
    	return Redirect::guest('admin/login');
	}

	$_user = login_user(Administrator::LABEL);
    // 账户在别处登录？退出当前会话
    if (Session::getId() != $_user->login_session)
    {
        Auth::admin()->logout();

        return Redirect::to('admin/login')->with('error', '您的账号已在别处登录，如果不是您本人操作，请及时修改密码');        
    }    
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (is_login()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| Session timeout Filter
|--------------------------------------------------------------------------
|
|判断当前登陆用户最后活动时间是否超出系统设定的超时时间
|
*/
Route::filter('session_timeout', function()
{
	// 开发环境不触发以下操作
	if (App::environment() == 'local')
	{
		return;
	}

	if (sessionTimeout()) 
	{
		// 用于 ajax 判定是否登录超时
		if (Request::ajax())
    	{
        	return Response::json(array('status' => 'timeout'));
    	}

    	// 清除 session 数据
		Session::flush();

		if (is_login())
		{
			return Redirect::guest('login/timeout');
		}
		return Redirect::guest('admin/login/timeout');
	}
});

/*
|--------------------------------------------------------------------------
| User Verify Step Check
|--------------------------------------------------------------------------
|
|判断当前登陆用户首次三步验证情况
|
*/
Route::filter('verifyStepCheck', function()
{

	$_user = login_user();

	// 是否完成了首次验证
	if ($_user->has_verified() ||
			Request::ajax() ||
			Request::is('upload*') ||
			//Request::is('profile/email-verify*') ||
			Request::is('profile/security*') ||
			Request::is('profile/verify*')) 
	{
		return;
	}

	/* 邮箱未验证
	if (!$_user->is_email_verified) 
	{
		return Redirect::to('profile/email-verify');
	}*/
	// 手机
	if (!empty($_user->mobile)) 
	{
		return Redirect::to('profile/security');
	}
	// 身份
	if (!$_user->is_id_verified) 
	{
		return Redirect::to('profile/verify');
	}
});



/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


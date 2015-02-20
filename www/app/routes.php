<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/*
 |--------------------------------------------------------------------------
 | Front-end Routes
 |--------------------------------------------------------------------------
 |
 */
/**
 * 页眉页脚
 */
Route::get('/', 'Controllers\HomeController@index');
Route::get('personal', 'Controllers\HomeController@getPersonal');
Route::get('business', 'Controllers\HomeController@getBusiness');
Route::get('news', 'Controllers\HomeController@getNews');
Route::get('coming-soon', 'Controllers\HomeController@getComingSoon');
Route::get('privacy', 'Controllers\HomeController@getPrivacy');
Route::get('legal', 'Controllers\HomeController@getLegal');

/**
 * 通用功能
 */
// 通用信息查询，无需登录
Route::controller('query', 'Controllers\QueryController');
// 文件上传
Route::Controller('upload', 'Controllers\UploadController');
// 信息验证
Route::controller('valid', 'Controllers\ValidController');

/**
 * 业务逻辑
 */
// 用户登录
Route::controller('login', 'Controllers\Login\LoginController');
Route::controller('password', 'Controllers\Login\PasswordController');
Route::get('logout', 'Controllers\Login\LoginController@getLogout');

// 用户注册
Route::controller('register', 'Controllers\Register\RegisterController');
Route::get('activate/confirm/{uid}/{token}', 'Controllers\Register\ConfirmController@index');

// 用户个人档案
Route::controller('profile', 'Controllers\Profile\ProfileController');

// 首页
Route::controller('home', 'Controllers\Home\HomeController');


/*
 |--------------------------------------------------------------------------
 | Admin Routes
 |--------------------------------------------------------------------------
 |
 */
Route::group(array('prefix' => 'admin'), function(){
	
	// 管理员注册
	Route::get('users/signup/{id}/{token}', 'Controllers\Register\RegisterController@getManagerSignup');
	Route::post('users/signup/{id}/{token}', 'Controllers\Register\RegisterController@postManagerSignup');
	
	// 登录
	Route::controller('login', 'Controllers\Admin\LoginController');
	Route::controller('password', 'Controllers\Admin\PasswordController');
	Route::get('logout', 'Controllers\Admin\LoginController@getLogout');
	
	// 我的工作台
	Route::get('/', 'Controllers\Admin\CenterController@getIndex');
	Route::controller('center', 'Controllers\Admin\CenterController');

	// 认证审核
	Route::controller('audit', 'Controllers\Admin\AuditController');

	// 用户管理
	Route::get('customers/export', 'Controllers\Admin\CustomerController@getExport');
	Route::get('customers/{type}', 'Controllers\Admin\CustomerController@getIndex');
	Route::controller('customers', 'Controllers\Admin\CustomerController');
	
	// 内容管理
	Route::controller('content', 'Controllers\Admin\ContentController');

	// 统计报表
	Route::controller('report', 'Controllers\Admin\ReportController');

    // 系统设置
    Route::controller('setting', 'Controllers\Admin\SettingController');
    
    // 文件上传
    Route::Controller('upload', 'Controllers\Admin\UploadController');

    // 管理员管理
    Route::controller('users', 'Controllers\Admin\UserController');
    Route::controller('profile', 'Controllers\Admin\ProfileController');

    // 日志管理
    Route::controller('logs', 'Controllers\Admin\LogController'); 

});



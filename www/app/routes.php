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

// Front-end Module
Route::get('/', 'Controllers\HomeController@index');
Route::get('personal', 'Controllers\HomeController@getPersonal');
Route::get('business', 'Controllers\HomeController@getBusiness');
Route::get('news', 'Controllers\HomeController@getNews');
Route::post('lock', 'Controllers\HomeController@postLock');
Route::get('refresh', 'Controllers\HomeController@getRefresh');
Route::get('coming-soon', 'Controllers\HomeController@getComingSoon');
Route::get('privacy', 'Controllers\HomeController@getPrivacy');
Route::get('legal', 'Controllers\HomeController@getLegal');
Route::get('invest-list-pawn', 'Controllers\HomeController@getInvestPawnList');

// Login
Route::controller('login', 'Controllers\Login\LoginController');
Route::controller('password', 'Controllers\Login\PasswordController');
Route::get('logout', 'Controllers\Login\LoginController@getLogout');

// register
Route::controller('register', 'Controllers\Register\RegisterController');

// Profile Module
Route::controller('profile', 'Controllers\Profile\ProfileController');

// Add Fund Module
Route::controller('fund', 'Controllers\Home\FundController');


// Withdraw Module
Route::controller('withdraw', 'Controllers\Home\WithdrawController');

// 债权转让
Route::controller('debt', 'Controllers\Debt\DebtController');

// 还款方式
Route::controller('repayment', 'Controllers\Repayment\RepaymentController');

// 我的借款
Route::controller('mydebt', 'Controllers\Home\MyDebtController');

// 我的投资
Route::controller('myinvest', 'Controllers\Home\MyInvestController');

// Upload Module
Route::Controller('upload', 'Controllers\UploadController');

// Validator Module
Route::controller('valid', 'Controllers\ValidController');


// 投资
Route::controller('invest', 'Controllers\Invest\InvestController');

// 理财
Route::controller('licai', 'Controllers\Licai\LicaiController');


Route::get('admin', 'Controllers\Admin\CenterController@getIndex');
Route::group(array('prefix' => 'admin'), function(){
	
	// 我的工作台
	Route::controller('center', 'Controllers\Admin\CenterController');

	// 债权管理
	Route::controller('debt', 'Controllers\Admin\DebtController');
		
	// 资金管理
	Route::controller('finance', 'Controllers\Admin\FinanceController');

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
    
    // 登录
    Route::controller('login', 'Controllers\Admin\LoginController');
    Route::controller('password', 'Controllers\Admin\PasswordController');
    Route::get('logout', 'Controllers\Admin\LoginController@getLogout');

    // Upload Module
    Route::Controller('upload', 'Controllers\Admin\UploadController');

    
    
    
    
    
    // Bank Management
    Route::controller('bank', 'Controllers\Admin\BankController');

    // Administrators Management
    Route::controller('users', 'Controllers\Admin\UserController');
    Route::controller('profile', 'Controllers\Admin\ProfileController');

    // User Logs
    Route::controller('logs', 'Controllers\Admin\LogController'); 

    // Notes
    Route::controller('note', 'Controllers\Admin\NoteController');

});






// Register Module
Route::get('activate/confirm/{uid}/{token}', 'Controllers\Register\ConfirmController@index');


Route::get('business-member-checkin/{id}/{token}', 'Controllers\Register\RegisterController@getBusinessMemberCheckin');

Route::get('admin/users/signup/{id}/{token}', 'Controllers\Register\RegisterController@getManagerSignup');
Route::post('admin/users/signup/{id}/{token}', 'Controllers\Register\RegisterController@postManagerSignup');

Route::controller('home', 'Controllers\Home\HomeController');
Route::controller('business', 'Controllers\Home\BusinessController');

Route::controller('quote', 'Controllers\QuoteController');

Route::controller('recipient', 'Controllers\Home\RecipientController');



// Lock rate Module
Route::controller('lockrate', 'Controllers\LockRate\LockRateController');

// Fx Module
Route::controller('fx', 'Controllers\LockRate\FxController');


// Rate Module
Route::controller('rate', 'Controllers\LockRate\RateController');

// Home Moduel
Route::get('receipt/{id}', 'Controllers\Home\HomeController@getReceipt');


Route::controller('query', 'Controllers\QueryController');

/* Management System */

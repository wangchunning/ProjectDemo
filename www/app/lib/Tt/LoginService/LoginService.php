<?php namespace Tt\LoginService;

use Auth;
use Request;
use Session;
use Ret;
use User;
use Administrator;
use UserLoginLog;
use AdminLoginLog;


/**
 */

class LoginService {

    /**
     * 
     * @param $user_name	
     * @param $password 	
     * @param $user_type	- user_web/admin/user_wechat/user_app/
     * 
     * @return 成功： session_id; 失败：0
     * 
     */
    public function login($user_name, $password, $login_from)
    {	

    	if ($login_from == UserLoginLog::LOGIN_FROM_ADMIN &&
    			Auth::admin()->attempt(array('user_name' => $user_name, 'password' => $password)))
    	{
    		$_user = login_user(Administrator::LABEL);
    		$_user->save_access_log($login_from);

    		return Session::getId();
    	}
    	else if ($login_from != UserLoginLog::LOGIN_FROM_ADMIN &&
    				Auth::client()->attempt(array('user_name' => $user_name, 'password' => $password)))
    	{
    		$_user = login_user(User::LABEL);
    		$_user->save_access_log($login_from);
    		
    		return Session::getId();    		
    	}

    	/**
    	 * 登录失败，记录ip，防止攻击
    	 */
    	$_login_ip 	= Request::getClientIp();
    	
    	$_log = new AdminLoginLog;
    	if ($login_from != UserLoginLog::LOGIN_FROM_ADMIN)
    	{
    		$_log 				= new UserLoginLog;
    		$_log->login_from	= $login_from;
    	}
    	
    	// 封ip
    	$_log->login_ip		= $_login_ip;
    	$_log->user_name	= $user_name;
    	
    	$_log->save();
    	 
    	return Ret::RET_FAILED;
    }

}
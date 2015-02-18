<?php
/**
 *  用户，管理员登陆相关辅助函数
 */

// ------------------------------------------------------------------------

/**
 * 验证登陆
 * 
 * @param  string  用户类型 user|admin
 * @return bool
 */
if ( ! function_exists('is_login'))
{
    function is_login($type = User::LABEL)
    {
        
        if (($type == Administrator::LABEL && 
        		Auth::admin()->check()) ||
        	($type == User::LABEL && 
        		Auth::client()->check()))
        {
            return true;
        }

        return false;

    }
}


/**
 * Session timeout 
 * 判断当前登陆用户最后活动时间是否超出系统设定的超时时间
 * 
 * @return bool 超时返回true
 */
if ( ! function_exists('sessionTimeout'))
{
	function sessionTimeout()
	{
        // 不存在登录会话?
        if ( !is_login(User::LABEL) && 
        		!is_login(Administrator::LABEL))
        {
            return TRUE;
        }

        // 过期时间统一为15分钟
        //$seconds = 900;
        $seconds = 9000;//测试
        
		if (Session::has('last_activity') &&
				Session::get('last_activity') + $seconds < time())
		{
			return TRUE;
		}
		
		// 未超时		
		Session::put('last_activity', time());

		return FALSE;
	}
}

/**
 * login_user
 * 获取登录用户示例，对于前台返回 User, 后台返回 Administrator
 * 如果 type 与当前用户不一致，则表示后台用户要访问前台功能，或者相反，则返回 404
 * 
 * @param  string  用户类型 user|admin
 * 
 * @return User/Administrator
 */
if ( ! function_exists('login_user'))
{
	function login_user($type = User::LABEL)
	{
		if ($type == User::LABEL)
		{
			return Auth::client()->user();	
		}
		else if ($type == Administrator::LABEL)
		{
			return Auth::admin()->user();
		}
		
		App::abort(404);

	}
}

/**
 * real_user
 * 
 */
if ( ! function_exists('real_user'))
{
    function real_user($type = User::LABEL)
    {
        return login_user($type);

    }
}

/**
 * real_user
 *
 */
if ( ! function_exists('ip_state'))
{
	function ip_state($ip)
	{
		$_area 		= '';
		
		$_reg_loc  	= Ip2loc::find($ip);
		if ($_reg_loc != 'N/A' &&
				!empty($_reg_loc))
		{
			if (isset($_reg_loc[1]) &&
					$_reg_loc[1] != 'N/A')
			{
				$_area = $_reg_loc[1]; // 省
			}
		}

		return $_area;
	}
}

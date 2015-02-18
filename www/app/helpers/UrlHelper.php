<?php
/**
 *  URL相关辅助函数
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * current_url函数
 *
 *
 * @access	public
 * @param	$hold_args	是否返回$_GET参数
 * @param	$unsets		需要销毁的参数
 * @return	string
 */
if ( ! function_exists('current_url'))
{
	function current_url($hold_args = FALSE, $unsets = array())
	{		
		$url = Request::url();
		
		$args = $_GET;
		
		// 输入地址不带$_GET参数?
		if( ! $hold_args)
		{
			return $url;
		}
		
		// 没有$_GET参数?
		if( ! $args)
		{
			return $url . '?';
		}
		
		if( ! is_array($unsets))
		{
			$unsets = array($unsets);
		}
		
		foreach($unsets as $key)
		{
			if( ! isset($args[$key]))
			{
				continue;
			}
			
			unset($args[$key]);
		}
		
		return $url . '?' . http_build_query($args);
	}
}

/**
 * 返回当前请求的方法名
 * 
 * @return string
 */
if ( ! function_exists('current_method'))
{
	function current_method()
	{
        $action = explode('@', Route::currentRouteAction());
        $method = end($action);
        
        return $method;
	}
}
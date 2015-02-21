<?php namespace Lib\Ret;

use Response;

/**
 * 标准化全局返回信息，如返回值，错误信息，json 等
 * 
 */
class Ret {
	
	/**
	 * 全局返回值
	 */
	const RET_SUCC 		= 0;
	const RET_FAILED	= 1;
	
	/**
	 * 全局错误码 0 - 999
	 */
	/* 尚未登录，无法访问 */
	const ERR_LOGIN_REQUIRED 	= 1;
	const ERR_FILE_UPLOAD 		= 2;
		
	/**
	 * 用户相关模块 1000 - 1999
	 */
	/* 用户注册信息填写错误 */
	const ERR_USER_REGISTER_INPUT 	= 1001;

		
	/**
	 * 取款模块 2000 - 2999
	 */
	/* 可用余额不足 */
	const ERR_WITHDRAW_BALANCE_NOT_ENOUGH	= 2000;
	
	/**
	 * 投资模块 3000 - 3999
	 */
	/* 可用余额不足 */	
	const ERR_INVEST_BALANCE_NOT_ENOUGH		= 3000;
	
	/**
	 * 错误码所对应的文字描述。
	 * 
	 * 这里的错误描述只是针对错误码的描述，不涉及业务逻辑信息，比如，
	 * “用户余额不足”，而不会涉及到用户当前余额；“注册信息填写错误”，而不会涉及到具体字段。
	 * 
	 * 带有业务逻辑的错误信息是在 controller 中填写，比如 “用户余额不足，当前余额 XXX”
	 * 
	 */
	public static $ERR_MSG_ARR = array(
		/* 默认值 */	
		0							=> '',	
		/**
		 * 通用错误信息
		 */
		self::ERR_LOGIN_REQUIRED	=> '尚未登录',
		self::ERR_FILE_UPLOAD		=> '文件上传失败',
		/**
		 * 用户相关 
		 */
		self::ERR_USER_REGISTER_INPUT	=> '注册信息填写错误',
			
	);
	
	/**
	 * 统一 json 返回格式，用于所有功能点，如  controller, filter ... 
	 * 
	 * @param	status	RET_SUCC, RET_FAILED
	 * @param	data		返回给客户端的数组
	 * @param	err_code	全局错误代码，本类中定义
	 * @param	err_msg		错误信息
	 * 
	 * @return \Response Encoded json data string
	 * 
	 *	{
	 *		'status'	: 0/1,
	 *		'err_code'	: error code,
	 *		'err_msg'	: '错误信息，参考 self::$ERR_MSG_ARR',
	 *		'err_desc'	: '错误描述, 包含逻辑错误信息，比如 ，“用户余额不足，当前余额 XXX”',
	 *		'data': [
	 *			'key1': 'value1',
	 *			'key2': 'value2',
	 *			......
	 *		]
	 *	}
	 */
	public static function json($status, $data = array(), $err_code = 0, $err_desc = '')
	{
		$_ret_arr = array();
		
		$_ret_arr['status']		= $status;
		$_ret_arr['data']		= $data;
		$_ret_arr['err_code']	= $err_code;
		$_ret_arr['err_msg']	= self::$ERR_MSG_ARR[$err_code];
		$_ret_arr['err_desc']	= $err_desc;
		
		return Response::json($_ret_arr);
	}
}

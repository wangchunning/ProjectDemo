<?php
/**
 *  URL相关辅助函数
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * pretty_str
 *
 * @return	string
 */
if ( ! function_exists('pretty_str'))
{
	function pretty_str($str, $str_max_length = 15)
	{		
		$_pad_str = " ...";

		$_str = trim($str);
		$_str_len = strlen($_str);

		if ($_str_len < $str_max_length)
		{
			return $_str;
		}

		return substr($_str, 0, $str_max_length) . $_pad_str;

	}
}

function pretty_real_name($real_name)
{
	$_name_length = mb_strlen($real_name);
	
	$_format = '* %s';
	if ($_name_length > 2)
	{
		$_format = '** %s';
	}
	
	return sprintf($_format, mb_substr($real_name, $_name_length - 1, 1, 'utf-8'));
}

function pretty_account_number($account_number)
{
	return sprintf('%s **** %s', 
					substr($account_number, 0, 4),
					substr($account_number, -4));
}

function pretty_date($date)
{
	return substr($date, 0, 16);
}

function format_account_number($number)
{
	$_ret 		= '';
	$_length 	= strlen($number);
	
	for($_i = 0; $_i < $_length; $_i++)
	{
		if ($_i % 4 == 0)
		{
			$_ret .= ' ';
		}
		$_ret .= $number[$_i]; 
	}
	
	return $_ret;
}


function send_sms($mobile, $text)
{
	if (empty($text) ||
			empty($mobile))
	{
		return;
	}
	
	$_phone_code = '86';
	// 发送 SMS
	$sms = array(
		'to'   => '+' . $_phone_code . $mobile,
		'text' => $text
	);
	Queue::push(function($job) use ($sms)
	{
		Sms::send($sms);
		$job->delete();
	});
}

function make_alert_link($desc, $url)
{
	return sprintf('<a href="%s" class="alert-link">%s</a>',
			url($url),
			$desc
	);
}

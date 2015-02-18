<?php namespace Tt\Fee;

use Config;

class Fee {	
	
	public static function fund_fee()
	{
		$_cfg	= Config::get('fee');
		
		return $_cfg['fund'][0];
	}
	
	public static function withdraw_fee()
	{
		$_cfg	= Config::get('fee');
	
		return $_cfg['withdraw'];
	}
}
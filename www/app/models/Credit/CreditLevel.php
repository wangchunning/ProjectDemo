<?php namespace Tt\Model;

use Config;

class CreditLevel extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'credit_level';
    
	const LEVEL_A	= 5;
	const LEVEL_B	= 4;
	const LEVEL_C	= 3;
	const LEVEL_D	= 2;
	const LEVEL_E	= 1;
	const LEVEL_F	= 0;

  
	public static function config()
	{
		$_cfg	= Config::get('credit_level');
		
		return $_cfg;
	}
	
	public static function color($level)
	{
		$_cfg	= CreditLevel::config();
		
		return $_cfg[$level]['color'];
	}
	public static function desc($level)
	{
		switch($level)
		{
			case CreditLevel::LEVEL_A:
				return 'AA';
				
			case CreditLevel::LEVEL_B:
				return 'A';
					
			case CreditLevel::LEVEL_C:
				return 'BB';
						
			case CreditLevel::LEVEL_D:
				return 'B';
							
			case CreditLevel::LEVEL_E:
				return 'CC';
								
			case CreditLevel::LEVEL_F:
				return 'C';
		}
		
		return '';
	}
}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorldwideCurrencyPair2rateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rates', function(Blueprint $table)
		{
			DB::table('rates')->delete();

			//  AUD\USD\EUR\CAD\HKD\NZD\GBP\SGD\JPY\CNY 
			$_currencies = array(
				/* AUD */
				'AUDUSD', 'AUDCNY', 'AUDCAD', 'AUDHKD', 
				'AUDJPY', 'AUDNZD', 'AUDSGD', 'EURAUD', 'GBPAUD', 
				/* USD */
				'USDCAD', 'USDHKD', 'USDJPY', 'USDSGD',
				/* CNY */
				'HKDCNY','JPYCNY','NZDCNY','EURCNY',
				'CADCNY','USDCNY','SGDCNY','GBPCNY',				
				/* EUR */
				'EURUSD', 'EURCAD', 'EURGBP', 'EURHKD', 
				'EURJPY', 'EURNZD', 'EURSGD', 
				/* GBP */
				'GBPCAD', 'GBPHKD', 'GBPJPY', 'GBPNZD', 
				'GBPSGD', 'GBPUSD', 
				/* CAD */
				'CADJPY', 'CADSGD', 'CADHKD',  
				/* HKD */
				'HKDJPY',
				/* JPY */
				/* NZD */
				'NZDCAD', 'NZDHKD', 'NZDJPY', 'NZDSGD', 
				'NZDUSD', 
				/* SGD */
				'SGDJPY', 'SGDHKD',

			);       

			$_insert_data = array(); 
			foreach ($_currencies as $_currency)
			{
				$_insert_data[] = array(
					'from'  => substr($_currency, 0, 3),
					'to'    => substr($_currency, 3),
				);
			}

			DB::table('rates')->insert($_insert_data);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rates', function(Blueprint $table)
		{
			//
		});
	}

}
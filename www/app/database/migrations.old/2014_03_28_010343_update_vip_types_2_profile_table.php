<?php

use Illuminate\Database\Migrations\Migration;

class UpdateVipTypes2ProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "ALTER TABLE `profiles`" .
					"CHANGE `is_vip` `vip_type` varchar(20) NOT NULL DEFAULT 'Basic' ";

		DB::statement($_sql);

		$_sql = "UPDATE `profiles` SET vip_type = 'Basic' WHERE vip_type = 0";
		DB::statement($_sql);

		$_sql = "UPDATE `profiles` SET vip_type = 'Silver' WHERE vip_type = 1";
		DB::statement($_sql);		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$_sql = "ALTER TABLE `profiles`" .
					"CHANGE `vip_type` `is_vip` smallint(6) NOT NULL DEFAULT '0' ";
		DB::statement($_sql);

		$_sql = "UPDATE `profiles` SET vip_type = 0 WHERE vip_type = 'Basic'";
		DB::statement($_sql);

		$_sql = "UPDATE `profiles` SET vip_type = 1 WHERE vip_type != 'Basic'";
		DB::statement($_sql);
	}

}
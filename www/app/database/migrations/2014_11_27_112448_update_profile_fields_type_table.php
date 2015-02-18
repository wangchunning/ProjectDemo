<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProfileFieldsTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "ALTER TABLE `user_profile` " .
				"CHANGE `company_type` `company_type` tinyint unsigned NOT NULL DEFAULT 0,".
				"CHANGE `company_category` `company_category` tinyint unsigned NOT NULL DEFAULT 0"
		;
		DB::statement($_sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}

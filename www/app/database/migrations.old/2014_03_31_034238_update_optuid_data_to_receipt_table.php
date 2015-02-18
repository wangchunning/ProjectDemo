<?php

use Illuminate\Database\Migrations\Migration;

class UpdateOptuidDataToReceiptTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "UPDATE `receipts` SET opt_uid = uid";
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
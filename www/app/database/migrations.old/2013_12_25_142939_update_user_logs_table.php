<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateUserLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `user_logs` CHANGE `obj_id` `obj_id` VARCHAR( 18 ) NOT NULL ");
		DB::statement("ALTER TABLE `user_logs` ADD `customer_id` INT( 11 ) UNSIGNED NOT NULL AFTER `obj_id` ");
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
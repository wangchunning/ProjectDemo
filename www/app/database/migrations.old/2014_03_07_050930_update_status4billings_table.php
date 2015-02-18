<?php

use Illuminate\Database\Migrations\Migration;

class UpdateStatus4billingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// add status 'void'
		DB::statement("ALTER TABLE `billings` CHANGE `status` `status` ENUM('Pending','Completed','Voided') NOT NULL ");
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
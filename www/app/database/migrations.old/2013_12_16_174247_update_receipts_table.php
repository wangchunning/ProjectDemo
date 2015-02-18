<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `receipts` CHANGE `status` `status` ENUM( 'Waiting for fund', 'Processing', 'Completed', 'Voided', 'Canceled', 'Uncompleted' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
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
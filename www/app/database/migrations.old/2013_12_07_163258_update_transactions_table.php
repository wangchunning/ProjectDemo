<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `transactions` CHANGE `status` `status` ENUM( 'Waiting for fund', 'Completed', 'Voided', 'Canceled', 'Pending' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ");
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
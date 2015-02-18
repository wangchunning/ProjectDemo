<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTransactionsNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `transaction_notes` CHANGE `type` `type` ENUM( 'transaction', 'receipt' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'transaction'");
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
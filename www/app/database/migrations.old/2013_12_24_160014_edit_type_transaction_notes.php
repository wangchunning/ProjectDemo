<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EditTypeTransactionNotes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE  `transaction_notes` CHANGE  `type`  `type` ENUM(  'transaction',  'receipt',  'overview' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  'transaction'");
		DB::statement("ALTER TABLE  `transaction_notes` ADD  `operator_id` INT UNSIGNED NOT NULL AFTER  `uid`");
		DB::statement("ALTER TABLE  `transaction_notes` CHANGE  `item_id`  `item_id` VARCHAR( 18 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL");
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
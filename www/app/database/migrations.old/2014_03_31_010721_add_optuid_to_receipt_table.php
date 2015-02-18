<?php

use Illuminate\Database\Migrations\Migration;

class AddOptuidToReceiptTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// operator uid used for business member
		Schema::table('receipts', function($table) 
		{
			$table->integer('opt_uid')->unsigned()->after('uid');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// 
		Schema::table('receipts', function($table)
		{
    		$table->dropColumn('opt_uid');
		});
	}

}
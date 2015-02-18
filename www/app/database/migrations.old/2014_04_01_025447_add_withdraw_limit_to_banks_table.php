<?php

use Illuminate\Database\Migrations\Migration;

class AddWithdrawLimitToBanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// add columns
		Schema::table('banks', function($table) 
		{
			$table->string('withdraw_limit', 60)->nullable()->after('limit');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('banks', function($table)
		{
    		$table->dropColumn('withdraw_limit');
		});
	}

}
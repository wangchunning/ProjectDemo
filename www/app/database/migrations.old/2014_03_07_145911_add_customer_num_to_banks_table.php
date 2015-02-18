<?php

use Illuminate\Database\Migrations\Migration;

class AddCustomerNumToBanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('banks', function($table) 
		{
			$table->tinyInteger('as_customer_num')->unsigned()->default(0)->after('account_number');
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
    		$table->dropColumn('as_customer_num');
		});
	}

}
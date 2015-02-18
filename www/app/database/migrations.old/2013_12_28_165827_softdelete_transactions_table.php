<?php

use Illuminate\Database\Migrations\Migration;

class SoftdeleteTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('transactions', function($table)
        {
    		$table->softDeletes();
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
	}

}
<?php

use Illuminate\Database\Migrations\Migration;

class AddStatusToBillingDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('billing_detail', function($table) 
		{

			$table->enum('status', array('Pending','Completed','Voided'))
					->default('Pending')->after('bill_id');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('billing_detail', function($table)
		{
    		$table->dropColumn('status');
		});
	}

}
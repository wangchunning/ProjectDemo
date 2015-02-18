<?php

use Illuminate\Database\Migrations\Migration;

class UpdateRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rates', function($table)
		{
    		$table->integer('order')->default(0)->after('vip_ask');
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
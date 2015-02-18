<?php

use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('rates');

        Schema::create('rates', function($table)
        {
			$table->string('from', 6);
			$table->string('to', 6);
			$table->integer('bid')->default(0);
			$table->integer('ask')->default(0);			
			$table->integer('vip_bid')->default(0);
			$table->integer('vip_ask')->default(0);
			$table->timestamps();

			$table->primary(array('from', 'to'));
        });		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rates');
	}

}
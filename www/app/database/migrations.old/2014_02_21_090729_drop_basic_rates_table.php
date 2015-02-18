<?php

use Illuminate\Database\Migrations\Migration;

class DropBasicRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('basic_rates');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('basic_rates');

        Schema::create('basic_rates', function($table)
        {
			$table->increments('id');
			$table->string('day', 10);
			$table->string('currency');
			$table->string('rate');			
			$table->string('operator_name');			
			$table->string('operator_id');
			$table->timestamps();
        });
	}

}
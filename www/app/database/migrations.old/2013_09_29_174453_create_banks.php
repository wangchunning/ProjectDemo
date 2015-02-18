<?php

use Illuminate\Database\Migrations\Migration;

class CreateBanks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('banks');

        Schema::create('banks', function($table)
        {

			$table->increments('id');
			$table->string('swift_code', 15);
			$table->string('bank', 128);
			$table->string('branch', 128);
			$table->string('unit_number', 128);
			$table->string('street_number', 60);
			$table->string('street', 128);
			$table->string('street_type', 60);
			$table->string('city', 60);
			$table->string('state', 60);
			$table->string('postcode', 60);			
			$table->string('account', 128);
			$table->string('account_number', 128);
			$table->string('BSB', 60);
			$table->string('currency', 128);
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->timestamps();

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('banks');
	}

}
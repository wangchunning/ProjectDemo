<?php

use Illuminate\Database\Migrations\Migration;

class CreateSwiftCode extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('swift_code');

        Schema::create('swift_code', function($table)
        {
			$table->increments('id');
			$table->string('swift_code', 15);
			$table->string('country', 125);
			$table->string('branch', 125);
			$table->string('branch_cn', 125)->nullable();
			$table->string('city', 125);
			$table->string('city_cn', 125)->nullable();
			$table->string('bank', 125);
			$table->string('bank_cn', 125)->nullable();
			$table->string('location')->nullable();
			$table->string('address')->nullable();
			$table->tinyInteger('ifchina')->unsigned()->default(0);
			$table->string('version', 20);

			$table->index('swift_code');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('swift_code');
	}

}
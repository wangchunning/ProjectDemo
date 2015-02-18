<?php

use Illuminate\Database\Migrations\Migration;

class CreateRecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('recipients');

        Schema::create('recipients', function($table)
        {

			$table->increments('id');
			$table->integer('uid')->unsigned();
			$table->string('transfer_country', 60)->nullable();
			$table->string('swift_code', 120)->nullable();
			$table->string('bank_name', 120)->nullable();
			$table->string('branch_name', 120)->nullable();
			$table->string('account_bsb', 120)->nullable();
			$table->string('account_number', 120);
			$table->string('account_name', 120);
			$table->string('address', 120)->nullable();
			$table->string('address_2', 120)->nullable();
			$table->string('postcode', 20)->nullable();
			$table->string('phone', 60)->nullable();
			$table->string('country', 60)->nullable();
			$table->timestamps();

			$table->foreign('uid')->references('uid')->on('users');
        });		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recipients');
	}

}
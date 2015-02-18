<?php

use Illuminate\Database\Migrations\Migration;

class CreateBalancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('balances');

        Schema::create('balances', function($table)
        {

			$table->increments('id');
			$table->integer('uid')->unsigned();
			$table->string('currency', 5);
			$table->float('amount');
			$table->float('frozen_amount');
			$table->timestamps();

			$table->foreign('uid')->references('uid')->on('users');
			$table->index('currency');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('balances');
	}

}
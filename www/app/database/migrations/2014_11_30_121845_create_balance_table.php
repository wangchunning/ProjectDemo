<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('user_balance');

        Schema::create('user_balance', function($table)
        {
			$table->integer('uid')->unsigned();
			$table->decimal('available_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('frozen_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('total_amount', 12, 2)->unsinged()->default(0);
			
			$table->timestamps();

			$table->primary('uid');
			
			$table->engine = 'InnoDB';
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_balance');
	}

}

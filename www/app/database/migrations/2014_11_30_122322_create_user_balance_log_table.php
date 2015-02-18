<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBalanceLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('user_balance_log');
        
        Schema::create('user_balance_log', function($table)
        {
            // Table columns
        	$table->increments('id');
            $table->integer('uid')->unsigned()->default(0);
            $table->tinyInteger('type')->unsigned()->default(0);
			$table->decimal('credit_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('debt_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('frozen_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('available_balance', 12, 2)->unsinged()->default(0);
            $table->text('desc');

            $table->timestamps();

            // Table indexes
            $table->index('uid');
            
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
		Schema::drop('user_balance_log');
	}

}

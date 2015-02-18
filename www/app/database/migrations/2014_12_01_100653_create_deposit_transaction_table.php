<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('deposit_transaction');
        
        Schema::create('deposit_transaction', function($table)
        {
            // Table columns
        	$table->increments('id');
            $table->integer('uid')->unsigned()->default(0);
            $table->tinyInteger('type')->unsigned()->default(0);
			$table->char('receipt_number', 14)->default('');
			$table->decimal('deposit_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('available_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('fee_amount', 6, 2)->unsinged()->default(0);

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
		Schema::drop('deposit_transaction');
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('withdraw_transaction');
        
        Schema::create('withdraw_transaction', function($table)
        {
            // Table columns
        	$table->increments('id');
            $table->integer('uid')->unsigned()->default(0);
			$table->char('receipt_number', 14)->default('');
			
            $table->string('bank_name', 30)->default('');
            $table->string('bank_full_name', 50)->default('');            
            $table->string('bank_city', 20)->default('');
            $table->string('bank_state', 10)->default('');           
			$table->bigInteger('account_number')->unsinged()->default(0);
			
			$table->decimal('total_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('available_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('fee_amount', 6, 2)->unsinged()->default(0);
			
			$table->tinyInteger('status')->unsigned()->default(0);
			
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
		Schema::drop('withdraw_transaction');
	}

}

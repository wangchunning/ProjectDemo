<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransactionFeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('transaction_fee');

        Schema::create('transaction_fee', function($table)
        {

			$table->increments('id');
			$table->string('type', 20);
			$table->decimal('amount', 8, 2);
			$table->string('currency', 5);

			$table->timestamps();
			$table->engine = 'InnoDB';

        });

        $insert_data['type'] 	= 'Withdraw';
        $insert_data['amount'] 	= '15';
        $insert_data['currency']= 'AUD';

        DB::table('transaction_fee')->insert($insert_data);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transaction_fee');
	}

}
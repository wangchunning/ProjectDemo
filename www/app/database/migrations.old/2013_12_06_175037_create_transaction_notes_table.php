<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransactionNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('transaction_notes');

        Schema::create('transaction_notes', function($table)
        {
            $table->increments('id');
			$table->integer('uid')->unsigned();
			$table->enum('type', array('transaction', 'reciept'))->default('transaction');
        	$table->string('item_id', 18);					
        	$table->text('desc');
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
		Schema::drop('transaction_notes');
	}

}
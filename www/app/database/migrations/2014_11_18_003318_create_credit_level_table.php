<?php

use Illuminate\Database\Migrations\Migration;

class CreateCreditLevelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('credit_level');

        Schema::create('credit_level', function($table)
        {
            // Table columns
            $table->increments('id');
            $table->string('name', 32);
            $table->tinyInteger('level')->unsigned()->default(0);             
            $table->integer('credit_min')->default(0);
            $table->integer('credit_max')->default(0);
            $table->string('image', 128);
            $table->decimal('margin_rate', 5, 2)->unsigned()->default(0);
            $table->text('desc');

            $table->timestamps();

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
		Schema::drop('credit_level');
	}

}
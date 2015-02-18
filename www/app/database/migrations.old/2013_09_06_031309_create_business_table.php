<?php

use Illuminate\Database\Migrations\Migration;

class CreateBusinessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('business');

        Schema::create('business', function($table)
        {
            // Table columns
            $table->integer('uid')->unsigned();
            $table->string('business_name', 128);
            $table->string('abn', 128);
            $table->string('unit_number', 128);
            $table->string('street_number', 60);
            $table->string('street', 128);
            $table->string('street_type', 60);
            $table->string('city', 60);
            $table->string('state', 60);
            $table->string('postcode', 60);
            $table->timestamps();
            $table->softDeletes();

            // Table indexes
            $table->primary('uid');

            // Table foreign keys
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
		Schema::drop('business');
	}

}
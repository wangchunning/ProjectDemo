<?php

use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('profiles');

        Schema::create('profiles', function($table)
        {
            $table->integer('uid')->unsigned();
            $table->enum('type', array('personal', 'business'))->default('personal');
            $table->smallInteger('is_vip')->default(0);
            $table->integer('limits')->default(2000);
            $table->string('birth_day', 2);
            $table->string('birth_month', 2);
            $table->string('birth_year', 4);
            $table->enum('phone_type', array('Mobile', 'Work', 'Home'))->default('Mobile');
            $table->string('phone_number', 60)->nullable();
            $table->string('unit_number', 128);
            $table->string('street_number', 60);
            $table->string('street', 128);
            $table->string('street_type', 60);
            $table->string('city', 60);
            $table->string('state', 60);
            $table->string('postcode', 60);
            $table->enum('status', array('verified', 'unverified'))->default('unverified');
            $table->timestamps();
            $table->softDeletes();

            // Table indexes
            $table->primary('uid');
            $table->index('type');
            $table->index('is_vip');

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
		Schema::drop('profiles');
	}

}
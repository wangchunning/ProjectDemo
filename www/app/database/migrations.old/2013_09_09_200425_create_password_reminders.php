<?php

use Illuminate\Database\Migrations\Migration;

class CreatePasswordReminders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('password_reminders');

        Schema::create('password_reminders', function($table)
        {
        	$table->string('email')->nullable();
            $table->string('token')->nullable();
            $table->string('mobile')->nullable();
        	$table->string('secure_pin')->nullable();
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('password_reminders');
	}

}
<?php

use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('permissions');
		// Creates the permissions table
		Schema::create('permissions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('display_name');
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
		Schema::drop('permissions');
	}

}
<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserMappingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_mapping', function($table)
        {
            $table->integer('parent_id')->unsigned();
            $table->integer('user_id')->unsigned();
			
			$table->timestamps();
			$table->softDeletes();
            
            $table->foreign('parent_id')->references('uid')->on('users');
            $table->primary(array('parent_id', 'user_id'));
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
		//
		Schema::drop('user_mapping');
	}

}
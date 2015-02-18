<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorAccessLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('administrator_access_log');
        
        Schema::create('administrator_access_log', function($table)
        {
            // Table columns
        	$table->increments('id');
            $table->integer('uid')->unsigned()->default(0);
            $table->char('user_name', 16)->default('');
            $table->char('login_ip', 16)->default('');
            $table->char('login_ip_area', 10)->default('');
            $table->timestamp('login_at')->default('0000-00-00 00:00:00');

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
		Schema::drop('administrator_access_log');
	}

}

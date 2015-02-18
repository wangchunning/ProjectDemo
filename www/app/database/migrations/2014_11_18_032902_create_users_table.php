<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('users');

        Schema::create('users', function($table)
        {
            // Table columns
            $table->increments('uid');
            $table->char('user_name', 16)->default('');
            $table->tinyInteger('user_level')->unsigned()->default(0);          
            $table->char('login_password', 64)->default('');   
            $table->char('withdraw_password', 64)->default('');
            $table->char('mobile', 16)->default('');
            $table->char('email', 45)->default('');
            $table->smallInteger('trust_credit')->default(0);
            
            $table->char('register_ip', 16)->default('');
            $table->char('register_ip_area', 10)->default('');
            $table->char('login_ip', 16)->default('');
            $table->char('login_ip_area', 10)->default('');
            $table->timestamp('login_at')->default('0000-00-00 00:00:00');
            $table->char('login_session', 40)->default('');
            
            $table->tinyInteger('is_email_verified')->unsigned()->default(0);  
            $table->tinyInteger('is_id_verified')->unsigned()->default(0);

            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();


            // Table indexes
            $table->unique('user_name');
            $table->unique('email');
            $table->unique('mobile');

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
		Schema::drop('users');
	}

}

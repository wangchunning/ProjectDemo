<?php

use Illuminate\Database\Migrations\Migration;

class CreateVerificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('verifications');

        Schema::create('verifications', function($table)
        {
            // Table columns
        	$table->integer('uid')->unsigned();
        	$table->smallInteger('email_verified')->default(0);
            $table->smallInteger('photo_id_verified')->default(0);
            $table->smallInteger('addr_proof_verified')->default(0);
        	$table->smallInteger('security_verified')->default(0);
        	$table->string('email_verify_token')->nullable();
        	$table->string('photo_id_file')->nullable();
        	$table->string('addr_proof_file')->nullable();
        	$table->string('security_mobile')->nullable();
        	$table->string('mobile_verify_pin')->nullable();
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
		Schema::drop('verifications');
	}

}
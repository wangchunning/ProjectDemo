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

        Schema::create('permissions', function($table)
        {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('display_name', 64);
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
        Schema::drop('permissions');
    }

}
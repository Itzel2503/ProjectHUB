<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permisions', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned();            
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->integer('type_id');
            $table->string('type'); // Aquí almacenarás el nombre del modelo
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
        Schema::dropIfExists('user_permisions');
    }
}

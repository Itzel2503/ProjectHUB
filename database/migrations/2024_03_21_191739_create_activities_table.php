<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sprint_id');
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('cascade');

            $table->unsignedBigInteger('user_id'); // El usuario que crea el reporte
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('delegate_id');
            $table->foreign('delegate_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('title');
            $table->string('content')->nullable();
            $table->longText('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('state')->nullable();
            $table->integer('points');
            $table->json('questions_points')->nullable();

            $table->boolean('look')->default(false);

            $table->dateTime('delegated_date');
            $table->dateTime('expected_date');
            $table->dateTime('progress')->nullable();
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('activities');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->unsignedBigInteger('user_id'); // El usuario que crea el reporte
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('delegate_id');
            $table->foreign('delegate_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('resolved_id')->nullable();
            $table->foreign('resolved_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('report_id')->nullable();

            $table->string('title');
            $table->text('content')->nullable();
            $table->boolean('image');
            $table->boolean('video');
            $table->boolean('file');
            $table->string('state');
            $table->string('comment');
            $table->integer('value')->default('0');
            $table->string('url')->nullable();
            $table->boolean('repeat')->default(false);

            $table->dateTime('progress')->nullable();

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
        Schema::dropIfExists('reports');
    }
}

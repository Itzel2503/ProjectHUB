<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBacklogFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backlog_files', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('backlog_id');
            $table->foreign('backlog_id')->references('id')->on('backlogs')->onDelete('cascade');
            
            $table->text('route');

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
        Schema::dropIfExists('backlog_files');
    }
}

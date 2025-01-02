<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatReportsActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_reports_activities', function (Blueprint $table) {
            $table->id();

            
            $table->unsignedBigInteger('report_id')->nullable();
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');

            $table->unsignedBigInteger('activity_id')->nullable();
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');

            $table->longText('message');
            $table->boolean('look');

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
        Schema::dropIfExists('chat_reports_activities');
    }
}

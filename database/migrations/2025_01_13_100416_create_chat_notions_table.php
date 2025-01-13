<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatNotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_notions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('notion_id');
            $table->foreign('notion_id')->references('id')->on('notions')->onDelete('cascade');
            
            $table->longText('message');
            $table->boolean('look');
            $table->string('document');
            $table->boolean('image');
            $table->boolean('file');

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
        Schema::dropIfExists('chat_notions');
    }
}

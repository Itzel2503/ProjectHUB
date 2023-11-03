<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementPrettyCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_pretty_cashes', function (Blueprint $table) {
            $table->id();

            $table->integer('pretty_cash_id')->unsigned();            
            $table->foreign('pretty_cash_id')->references('id')->on('pretty_cashes');

            $table->date('date');
            $table->string('concept');
            $table->float('entries');
            $table->float('outputs');
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
        Schema::dropIfExists('movement_pretty_cashes');
    }
}

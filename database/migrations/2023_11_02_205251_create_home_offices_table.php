<?php

use App\Models\HomeOffice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_offices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('reason', [
                HomeOffice::FAMILY,
                HomeOffice::PERSONAL,
                HomeOffice::DISEASE,
                HomeOffice::MEDICAL,
                HomeOffice::LEGAL,
                HomeOffice::OTHER,
            ]);
            $table->text('activities');
            $table->integer('delegate_activities');
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
        Schema::dropIfExists('home_offices');
    }
}

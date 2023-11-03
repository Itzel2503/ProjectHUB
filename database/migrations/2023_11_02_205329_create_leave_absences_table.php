<?php

use App\Models\LeaveAbsence;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_absences', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->date('date');
            $table->enum('salary', [
                LeaveAbsence::WITH_PAY,
                LeaveAbsence::WITHOUT_PAY,
            ]);
            $table->integer('delegate_activities');
            $table->string('document');
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
        Schema::dropIfExists('leave_absences');
    }
}

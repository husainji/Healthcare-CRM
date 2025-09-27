<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['Scheduled','Confirmed','Completed','Cancelled','No-Show'])->default('Scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['doctor_id','appointment_date','appointment_time']);
            $table->index(['patient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}

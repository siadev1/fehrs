<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_next_of_kins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('dob');
            $table->string('gender');
            $table->integer('phone_no')->null();
            $table->string('relationship');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_next_of_kins');
    }
};

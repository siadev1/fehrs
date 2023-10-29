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
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('drug_name');
            $table->integer('drug_quantity');
            $table->string('brand_name');
            $table->string('package_size');
            $table->string('manufacturer');
            $table->string('batch_no');
            $table->string('manufacturing_date');
            $table->string('expiration_date');
            $table->string('nafdac_number');
            $table->string('dosage_form');
            $table->string('concentration');
            $table->string('drug_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};

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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('card_no');
            $table->string('case_no');
            $table->string('contact');
            $table->unsignedInteger('age');
            $table->string('email')->nullable();
            $table->string('gender');
            $table->string('address');
            $table->decimal('weight', 8, 2);
            $table->string('cat_category');
            $table->json('treatment_required')->nullable();
            $table->string('bite_type')->nullable();
            $table->string('place_of_bite');
            $table->string('source');
            $table->string('severity')->nullable();
            $table->string('generic_name');
            $table->string('route');
            $table->string('brand_name');
            $table->string('dosage');
            $table->string('anti_rabies_dose');
            $table->date('anti_rabies_date');
            $table->string('tetanus_status');
            $table->string('tetanus_dose');
            $table->date('tetanus_date');
            $table->string('rabies_immunoglobulin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
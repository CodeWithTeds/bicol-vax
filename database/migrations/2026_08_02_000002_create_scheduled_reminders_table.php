<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_reminders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('patient_name');
            $table->string('email')->nullable();
            $table->string('dose_label');
            $table->unsignedSmallInteger('day_offset');
            $table->date('reminder_date');
            $table->time('reminder_time')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['appointment_id', 'day_offset']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_reminders');
    }
};

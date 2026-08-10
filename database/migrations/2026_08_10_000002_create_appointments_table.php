<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('consultation_type')->default('online');
            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('pending');
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('meeting_url')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'appointment_date']);
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

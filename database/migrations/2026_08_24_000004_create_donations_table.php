<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->string('donor_name', 150);
            $table->string('donor_email', 100);
            $table->string('donor_phone', 30)->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->decimal('amount', 15, 2);
            $table->foreignId('program_id')->nullable()->constrained('donation_programs')->nullOnDelete();
            $table->foreignId('donation_account_id')->constrained('donation_accounts')->restrictOnDelete();
            $table->string('transfer_proof_path')->nullable();
            $table->text('donor_notes')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

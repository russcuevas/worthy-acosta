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
        Schema::create('assistances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->string('type'); // Financial, Burial, Tent, Item Donation, Others
            $table->string('custom_type')->nullable(); // For Type = Others
            $table->string('assistance_given'); // Specific description / name
            $table->date('date');
            $table->decimal('amount', 14, 2)->default(0.00);
            $table->unsignedInteger('beneficiaries_count')->default(1);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['barangay_id', 'date']);
            $table->index('type');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistances');
    }
};

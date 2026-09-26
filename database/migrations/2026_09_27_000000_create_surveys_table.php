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
        // 1. Survey Periods (Date Coverage / Historical Records)
        Schema::create('survey_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "June 5-7, 2026"
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('sample_size')->nullable(); // Total sample size
            $table->string('methodology')->nullable(); // e.g. "Face-to-face random sampling"
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('start_date');
            $table->index('is_active');
        });

        // 2. Survey Records (Survey entries per candidate and barangay)
        Schema::create('survey_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_period_id')->constrained('survey_periods')->cascadeOnDelete();
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->string('candidate_name');
            $table->string('candidate_color', 20)->default('#075998'); // Hex color code
            $table->decimal('rating', 5, 2); // Percentage rating e.g. 45.50
            $table->integer('sample_size')->nullable(); // Respondents for this barangay
            $table->string('methodology')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('survey_period_id');
            $table->index('barangay_id');
            $table->index('candidate_name');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_records');
        Schema::dropIfExists('survey_periods');
    }
};

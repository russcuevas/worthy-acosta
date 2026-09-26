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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Issue Title or concise description
            $table->string('issue_type', 50); // Political, Community, Municipal-Wide, Policy, Infrastructure
            $table->boolean('is_municipal_wide')->default(false); // Municipal-Wide / All Barangays
            $table->text('who_affected'); // Individuals, groups, sectors, communities, or areas affected
            $table->text('details')->nullable(); // Background, explanation, developments, concerns
            $table->string('status', 50)->default('New'); // New, Ongoing, For Action, Resolved
            $table->string('priority', 50)->default('Medium'); // Low, Medium, High, Urgent
            $table->date('date_reported'); // Date first reported or encoded
            $table->text('action_taken')->nullable(); // Developments or action notes
            $table->text('resolution_notes')->nullable(); // Notes upon resolution
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('issue_type');
            $table->index('status');
            $table->index('priority');
            $table->index('date_reported');
            $table->index('is_municipal_wide');
        });

        Schema::create('issue_barangay', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('issues')->cascadeOnDelete();
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['issue_id', 'barangay_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_barangay');
        Schema::dropIfExists('issues');
    }
};

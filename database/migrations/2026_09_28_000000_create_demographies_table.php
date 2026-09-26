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
        // 1. Configurable Community Sectors (Fishermen, Farmers, Factory Workers, etc.)
        Schema::create('demographic_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color', 20)->default('#075998');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // 2. Demographic Records (Barangay -> Sector -> Number of Members)
        Schema::create('demographic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained('demographic_sectors')->cascadeOnDelete();
            $table->unsignedInteger('members_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('last_updated_date')->useCurrent();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['barangay_id', 'sector_id']);
            $table->index('barangay_id');
            $table->index('sector_id');
            $table->index('members_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demographic_records');
        Schema::dropIfExists('demographic_sectors');
    }
};

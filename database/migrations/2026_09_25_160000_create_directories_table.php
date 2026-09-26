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
        Schema::create('directories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->string('contact_type'); // Sectoral, Barangay Officials, Neighborhood Association, Coordinator, Leader, Supporter, Others
            $table->string('name'); // Full name of the contact
            $table->string('position'); // Position, designation, role, or title
            $table->string('contact_number')->nullable(); // Primary contact/mobile number
            $table->text('other_info')->nullable(); // Additional relevant notes or directory information
            $table->string('internal_label')->default('Saint'); // Saint, Sinner, Savable (User-defined internal directory category)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['barangay_id', 'contact_type']);
            $table->index('internal_label');
            $table->index('name');
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directories');
    }
};

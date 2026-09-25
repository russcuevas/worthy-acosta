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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('Upcoming'); // Upcoming, Past
            $table->string('attendance_status')->nullable()->default('For Confirmation'); // Confirmed, Tentative, Declined, For Confirmation
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->string('name'); // Event Name / Title
            $table->dateTime('event_datetime'); // Scheduled or actual date and time
            $table->string('venue'); // Location of the event
            $table->string('event_type'); // Municipal, Barangay, Sectoral, Political, Others
            $table->string('custom_type')->nullable(); // For Others custom description
            $table->string('who_invited'); // Person, organization, office, group, or official
            $table->text('contact_info')->nullable(); // Contact person, mobile, email, details
            $table->text('details')->nullable(); // Description and important info
            $table->text('request')->nullable(); // Any request made by organizer
            $table->boolean('speech_required')->default(false); // Speech: Yes or No
            $table->string('theme')->nullable(); // Official event theme
            $table->unsignedInteger('expected_attendees')->default(0); // Expected attendance
            $table->unsignedInteger('actual_attendees')->nullable(); // Total who attended
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['barangay_id', 'event_datetime']);
            $table->index(['status', 'event_datetime']);
            $table->index('event_type');
            $table->index('speech_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

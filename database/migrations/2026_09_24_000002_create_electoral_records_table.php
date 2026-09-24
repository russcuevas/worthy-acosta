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
        Schema::create('electoral_records', function (Blueprint $table) {
            $table->id();
            $table->string('year', 10)->index();
            $table->string('position', 100)->index();
            $table->unsignedTinyInteger('barangay_id')->index();
            $table->string('barangay_name', 100);
            $table->unsignedInteger('registered_voters')->default(0);
            $table->unsignedInteger('actual_votes')->default(0);
            $table->decimal('turnout_percentage', 5, 2)->default(0.00);
            $table->string('winner_name')->nullable();
            $table->string('winner_color', 30)->default('#075998');
            $table->unsignedInteger('winner_votes')->default(0);
            $table->json('candidates_data')->nullable();
            $table->timestamps();

            $table->unique(['year', 'position', 'barangay_id'], 'year_pos_bgy_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electoral_records');
    }
};

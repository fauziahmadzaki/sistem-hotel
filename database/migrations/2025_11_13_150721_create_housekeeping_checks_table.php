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
        Schema::create('housekeeping_checks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('housekeeper_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['done', 'needs_to_be_done'])->default('needs_to_be_done');
            $table->longText('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housekeeping_checks');
    }
};

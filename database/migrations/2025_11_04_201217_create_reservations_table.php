<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->string('identity', 16);
            $table->longText('notes')->nullable();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('total_guests')->default(1);
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->decimal('deposit', 10, 2)->default(0);
            $table->decimal('fines', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'card'])->default('cash');
            $table->enum('status', [ 'cancelled', 'checkin', 'pending', 'completed'])->default('checkin');
        });
    }

    /**
     * Hapus tabel jika di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

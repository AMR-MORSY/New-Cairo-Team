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
        Schema::create('modification_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modification_id')->constrained();
            $table->foreignId('PO_id')->constrained();
            $table->enum('status', ['active', 'completed', 'expired'])->default('active');
            $table->decimal('amount', 10, 2);
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modification_reservations');
    }
};

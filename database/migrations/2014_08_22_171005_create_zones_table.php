<?php

use App\Enums\Zones;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {

            $table->id();
            $table->string('name', 20);
            $table->enum('code', array_column(Zones::cases(), 'value'))->unique(); // cairo_east, cairo_north, etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->timestamps();

              $table->index(['area_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};

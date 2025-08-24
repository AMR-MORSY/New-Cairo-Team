<?php

use App\Enums\ModificationActions;
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
        Schema::create('modification__actions', function (Blueprint $table) {
            $table->id();
             $table ->enum('action',array_column(ModificationActions::cases(), 'value'))->unique();
             $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modification__actions');
    }
};

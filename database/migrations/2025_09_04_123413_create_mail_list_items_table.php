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
        Schema::create('mail_list_items', function (Blueprint $table) {
            $table->id();
            $table->string('item', 10)->nullable()->default('Mail');
            $table->text('description');
            $table->string('unit', 10)->nullable();
            $table->decimal('supply', 8, 2)->default(0.00);
            $table->decimal('installation', 8, 2)->default(0.00);
            $table->decimal('sup_inst', 8, 2)->default(0.00);
            $table->string('type', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_list_items');
    }
};

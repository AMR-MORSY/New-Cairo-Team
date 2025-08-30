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
        Schema::create('price_list_item_quotation', function (Blueprint $table) {
            $table->id();
             $table->foreignId('quotation_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
             $table->foreignId('price_list_item_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
             $table->decimal('supply_price',8,2);
             $table->decimal('install_price',8,2);
             $table->decimal('quantity',8,1);
             $table->string('scope',10);
             $table->decimal('item_price',12,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_list_item_quotation');
    }
};

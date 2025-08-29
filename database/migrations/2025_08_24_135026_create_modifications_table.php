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
        Schema::create('modifications', function (Blueprint $table) {
            $table->id();
            $table->string('site_code');
            $table->foreign('site_code')->references('site_code')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subcontractor_id')->constrained()->onDelete('cascade');
            $table->foreignId('requester_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('pending')->nullable();
            $table->foreignId('modification_status_id')->constrained('modification_status')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('action_owner')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('request_date');
            $table->date('d6_date')->nullable();
            $table->date('cw_date')->nullable();
            $table->string('wo_code', 20)->unique();
            $table->decimal('final_cost', 8, 2)->nullable();
            $table->decimal('est_cost', 8, 2);
            $table->boolean('reported')->default(false);
            $table->date('reported_at')->nullable();
            $table->softDeletes();
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('month', 20)->nullable();
            $table->integer('year')->nullable();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifications');
    }
};

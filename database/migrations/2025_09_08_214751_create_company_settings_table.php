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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->unsignedInteger('work_hours_per_month')->default(220);
            $table->enum('rounding_mode', ['up', 'nearest', 'down'])->default('up');
            $table->decimal('rounding_step', 6, 2)->default(0.05);
            $table->string('currency', 3)->default('BRL');
            $table->boolean('active_time_only')->default(true);
            $table->decimal('pricing_min_margin_pct', 5, 2)->default(30.00);
            $table->decimal('pricing_max_margin_pct', 5, 2)->default(50.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};

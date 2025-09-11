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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('old_version_id')->nullable()->constrained('recipes');
            $table->string('name', 140);
            $table->string('category', 80)->nullable();
            $table->decimal('production_qty', 12, 3);
            $table->unsignedInteger('preparation_min')->default(0);
            $table->unsignedInteger('resting_min')->default(0);
            $table->unsignedInteger('finishing_min')->default(0);
            $table->boolean('active_time_only')->default(true);
            $table->decimal('loss_pct', 5, 2)->default(0.00);
            $table->unsignedInteger('version')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

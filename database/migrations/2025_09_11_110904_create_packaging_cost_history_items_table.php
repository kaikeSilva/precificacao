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
        Schema::create('packaging_cost_history_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('packaging_id')->constrained('packagings')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->date('date');
            $table->decimal('pack_price', 12, 2);
            $table->decimal('current_unit_price', 12, 2);
            $table->string('source', 120)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['packaging_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_cost_history_items');
    }
};

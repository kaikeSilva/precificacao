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
        Schema::create('packagings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->foreignId('unit_id')->constrained('units');
            $table->unsignedInteger('pack_qty')->default(100);
            $table->decimal('pack_price', 12, 2);
            $table->decimal('unit_cost_cached', 12, 4);
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packagings');
    }
};

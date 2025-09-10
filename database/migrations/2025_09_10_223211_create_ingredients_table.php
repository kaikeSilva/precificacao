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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('name', 140);
            $table->enum('unit', ['g', 'kg', 'ml', 'l', 'un']);
            $table->decimal('pack_qty', 12, 3);
            $table->enum('pack_unit', ['g', 'kg', 'ml', 'l', 'un']);
            $table->decimal('pack_price', 12, 2);
            $table->decimal('loss_pct_default', 5, 2)->default(0.00);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};

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
        Schema::table('purchase_items', function (Blueprint $table) {
            // Drop existing foreign key and column for ingredient
            if (Schema::hasColumn('purchase_items', 'ingredient_id')) {
                $table->dropForeign(['ingredient_id']);
                $table->dropColumn('ingredient_id');
            }

            // Add polymorphic columns: item_type, item_id
            $table->string('item_type')->after('purchase_id');
            $table->unsignedBigInteger('item_id')->after('item_type');
            $table->index(['item_type', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            // Drop polymorphic columns
            if (Schema::hasColumn('purchase_items', 'item_type')) {
                $table->dropIndex(['item_type', 'item_id']);
                $table->dropColumn(['item_type', 'item_id']);
            }

            // Recreate ingredient_id with FK
            $table->foreignId('ingredient_id')
                ->after('purchase_id')
                ->constrained('ingredients')
                ->restrictOnDelete();
        });
    }
};

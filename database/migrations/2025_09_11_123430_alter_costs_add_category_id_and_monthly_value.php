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
        Schema::table('costs', function (Blueprint $table) {
            // Add new foreign key for category
            $table->foreignId('category_id')
                ->after('company_id')
                ->nullable()
                ->constrained('cost_categories');

            // Drop legacy category string column
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costs', function (Blueprint $table) {
            // Recreate legacy category column
            $table->string('category', 60)->nullable()->after('name');

            // Drop foreign key and column
            $table->dropConstrainedForeignId('category_id');
        });
    }
};

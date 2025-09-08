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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 120);
            $table->string('document', 32)->nullable();
            $table->foreignId('owner_user_id')->constrained('users');
            $table->string('timezone', 64)->default('America/Sao_Paulo');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

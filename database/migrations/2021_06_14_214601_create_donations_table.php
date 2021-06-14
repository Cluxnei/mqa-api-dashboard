<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['donation', 'reception']);
            $table->foreignId('from_company_id')->constrained('companies');
            $table->foreignId('to_company_id')->constrained('companies');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    final public function down(): void
    {
        Schema::dropIfExists('donations');
    }
}

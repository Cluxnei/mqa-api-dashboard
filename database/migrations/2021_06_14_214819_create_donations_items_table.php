<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    final public function up(): void
    {
        Schema::create('donations_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations');
            $table->foreignId('food_id')->constrained('foods');
            $table->foreignId('unit_id')->constrained('units');
            $table->double('amount');
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
        Schema::dropIfExists('donations_items');
    }
}

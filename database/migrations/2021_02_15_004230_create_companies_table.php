<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(1);
            $table->string('name');
            $table->string('cnpj')->unique();
            $table->string('phone');
            $table->string('email');
            $table->string('zipcode');
            $table->string('street');
            $table->string('neighborhood');
            $table->string('address_number');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->json('history')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
}

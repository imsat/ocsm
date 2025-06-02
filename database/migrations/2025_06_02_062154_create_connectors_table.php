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
        Schema::create('connectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charging_station_id')->constrained()->onDelete('cascade');
            $table->integer('connector_id');
            $table->string('status')->default('Unavailable');
            $table->string('error_code')->nullable();
            $table->string('info')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('vendor_error_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connectors');
    }
};

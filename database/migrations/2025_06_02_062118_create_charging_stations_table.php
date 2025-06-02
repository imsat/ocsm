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
        Schema::create('charging_stations', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('vendor');
            $table->string('model');
            $table->string('serial_number');
            $table->string('firmware_version')->nullable();
            $table->string('status')->default('Unavailable');
            $table->timestamp('last_heartbeat')->nullable();
            $table->integer('connector_count')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charging_stations');
    }
};

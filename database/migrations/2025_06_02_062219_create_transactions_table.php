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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charging_station_id')->constrained()->onDelete('cascade');
            $table->integer('connector_id');
            $table->integer('transaction_id');
            $table->string('id_tag');
            $table->timestamp('start_time');
            $table->timestamp('stop_time')->nullable();
            $table->integer('start_meter_value');
            $table->integer('stop_meter_value')->nullable();
            $table->string('stop_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('to_address');
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->string('weight');
            $table->boolean('isFlex');
            $table->string('value');
            $table->string('tracking_number');
            $table->foreignId('carrier_id')->constrained()->cascadeOnDelete();
            $table->string('attachment');
            $table->string('shipment_price');
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};

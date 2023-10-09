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
        Schema::create('purchases_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchases_id')->constrained('purchases')->cascadeOnUpdate();
            $table->foreignId('inventories_id')->constrained('inventories')->cascadeOnUpdate();
            $table->integer('qty');
            $table->string('price', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases_details');
    }
};

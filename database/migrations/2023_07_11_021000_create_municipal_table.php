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
        Schema::create('municipal', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->unsignedBigInteger('province_id');

            $table->foreign('province_id')->references('id')->on('province')->onDelete('cascade');
            $table->timestamps(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipal');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('discount_percent', 10)->nullable();
            $table->string('fixed_amount', 10)->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_details');
    }
};

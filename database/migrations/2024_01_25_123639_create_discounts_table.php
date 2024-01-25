<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('title', 50)->nullable();
            $table->string('description', 100)->nullable();
            $table->string('discount_percent', 10)->nullable();
            $table->string('fixed_amount', 10)->nullable();
            $table->dateTimeTz('expire_date', $precision = 0)->nullable();
            $table->smallInteger('capacity')->nullable();
            $table->foreignId('for_user')->nullable()->constrained(table: 'users', indexName: 'for_user_id');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};

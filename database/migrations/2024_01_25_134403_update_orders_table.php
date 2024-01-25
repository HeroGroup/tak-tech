<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->foreignId('discount_id')->after('transaction_id')->nullable()->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->dropForeign('orders_discount_id_foreign');
            $table->dropColumn('discount_id');
        });
    }
};

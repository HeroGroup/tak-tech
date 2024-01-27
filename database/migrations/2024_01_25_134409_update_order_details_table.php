<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function(Blueprint $table) {
            $table->foreignId('discount_detail_id')->after('product_id')->nullable()->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function(Blueprint $table) {
            $table->dropForeign('order_details_discount_detail_id_foreign');
            $table->dropColumn('discount_detail_id');
        });
    }
};

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
        Schema::table('services', function(Blueprint $table) {
            $table->foreignId('order_detail_id')->reference('id')->on('order_details')->nullable()->after('sold_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function(Blueprint $table) {
            $table->dropColumn('order_detail_id');
        });
    }
};

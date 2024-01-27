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
        Schema::table('discounts', function(Blueprint $table) {
            $table->foreignId('created_by')->references('id')->on('users')->after('created_at');
            $table->foreignId('updated_by')->references('id')->on('users')->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        
        Schema::table('discounts', function(Blueprint $table) {
            $table->dropForeign('discounts_created_by_foreign');
            $table->dropColumn('created_by');
            $table->dropForeign('discounts_updated_by_foreign');
            $table->dropColumn('updated_by');
        });
    }
};

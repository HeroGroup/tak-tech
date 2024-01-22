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
        Schema::table('transactions', function(Blueprint $table) {
            $table->string('description')->after('title')->nullable();
            $table->string('transfer_token', 9)->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function(Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('transfer_token');
        });
    }
};

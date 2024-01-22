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
        Schema::table('users', function(Blueprint $table) {
            $table->string('google_id')->after('password')->nullable();
            $table->string('google_token')->after('google_id')->nullable();
            $table->string('google_refresh_token')->after('google_token')->nullable();
            $table->string('apple_id')->after('password')->nullable();
            $table->string('apple_token')->after('apple_id')->nullable();
            $table->string('apple_refresh_token')->after('apple_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropColumn('google_id');
        $table->dropColumn('google_token');
        $table->dropColumn('google_refresh_token');
        $table->dropColumn('apple_id');
        $table->dropColumn('apple_token');
        $table->dropColumn('apple_refresh_token');
    }
};

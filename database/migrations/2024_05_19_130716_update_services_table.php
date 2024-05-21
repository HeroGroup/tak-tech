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
            $table->boolean('is_enabled')->after('panel_peer_id')->default(true);
            $table->integer('expire_days')->after('is_enabled')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function(Blueprint $table) {
            $table->dropColumn('is_enabled');
            $table->dropColumn('expire_days');
        });
    }
};

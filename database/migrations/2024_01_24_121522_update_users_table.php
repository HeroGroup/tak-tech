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
            $table->string('invite_code', 5)->after('wallet')->nullable();
            $table->foreignId('invitee')
                ->after('invite_code')
                ->nullable()
                ->constrained(table: 'users', indexName: 'invitee_user_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('invite_code');
            $table->dropColumn('invitee');
        });
    }
};

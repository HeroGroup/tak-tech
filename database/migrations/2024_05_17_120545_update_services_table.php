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
            $table->dropColumn('title');
            $table->dropColumn('server_id');
            $table->dropColumn('server_description');
            $table->dropColumn('status');
            $table->integer('panel_peer_id')->after('product_id');
            $table->string('conf_file', 150)->after('panel_peer_id');
            $table->string('qr_file', 150)->after('conf_file');
            $table->boolean('is_sold')->after('qr_file')->default(false);
            $table->timestamp('sold_at')->nullable()->after('is_sold');
            $table->bigInteger('owner')->unsigned()->nullable()->after('sold_at');
            $table->timestamp('activated_at')->nullable()->after('owner');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function(Blueprint $table) {
            $table->string('title', 100)->after('product_id');
            $table->string('server_id', 50)->after('title');
            $table->string('server_description', 100)->after('server_id');
            $table->string('status', 20)->after('server_description');
            $table->dropColumn('panel_peer_id');
            $table->dropColumn('conf_file');
            $table->dropColumn('qr_file');
            $table->dropColumn('is_sold');
            $table->dropColumn('owner');
            $table->dropColumn('sold_at');
            $table->dropColumn('activated_at');
        });
    }
};

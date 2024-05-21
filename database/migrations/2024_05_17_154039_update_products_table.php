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
        Schema::table('products', function(Blueprint $table) {
            $table->string('iType', 20)->nullable()->after('period');
            $table->decimal('allowed_traffic', 5, 2)->nullable()->after('iType');
            $table->tinyInteger('maximum_connections')->nullable()->after('allowed_traffic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function(Blueprint $table) {
            $table->dropColumn('iType');
            $table->dropColumn('allowed_traffic');
            $table->dropColumn('maximum_connections');
        });
    }
};

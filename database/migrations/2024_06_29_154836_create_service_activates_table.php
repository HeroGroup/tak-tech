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
        Schema::create('service_activates', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('service_id')->index();
            $table->string('api_call_status', 2)->default("-1");
            $table->string('api_call_message', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_activates');
    }
};

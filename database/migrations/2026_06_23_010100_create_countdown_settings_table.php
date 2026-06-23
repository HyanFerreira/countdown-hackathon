<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countdown_settings', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('timezone')->default('America/Sao_Paulo');
            $table->string('before_start_text');
            $table->string('running_text');
            $table->string('finished_text');
            $table->unsignedInteger('sync_version')->default(1);
            $table->dateTime('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countdown_settings');
    }
};

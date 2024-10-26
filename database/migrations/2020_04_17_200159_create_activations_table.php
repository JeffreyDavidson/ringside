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
        Schema::create('activations', function (Blueprint $table) {
            $table->id();
            $table->morphs('activatable');
            $table->datetime('started_at');
            $table->datetime('ended_at')->nullable();
            $table->timestamps();
        });
    }
};

<?php

use App\Models\EventMatch;
use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wrestler_championships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Title::class)->constrained();
            $table->foreignIdFor(EventMatch::class)->constrained();
            $table->foreignIdFor(Wrestler::class)->constrained();
            $table->datetime('won_at');
            $table->datetime('lost_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wrestler_championships');
    }
};

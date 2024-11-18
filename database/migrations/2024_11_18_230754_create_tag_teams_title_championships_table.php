<?php

use App\Models\EventMatch;
use App\Models\TagTeam;
use App\Models\Title;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tag_teams_title_championships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Title::class);
            $table->foreignIdFor(EventMatch::class);
            $table->foreignIdFor(TagTeam::class, 'new_champion_id');
            $table->foreignIdFor(TagTeam::class, 'foreign_champion_id');
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
        Schema::dropIfExists('tag_teams_title_championships');
    }
};

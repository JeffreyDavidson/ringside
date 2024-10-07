<?php

use App\Models\Manager;
use App\Models\TagTeam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manager_tag_team', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Manager::class);
            $table->foreignIdFor(TagTeam::class);
            $table->dateTime('hired_at');
            $table->dateTime('left_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_tag_team');
    }
};

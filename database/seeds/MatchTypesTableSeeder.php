<?php

use App\Models\MatchType;
use Illuminate\Database\Seeder;

class MatchTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MatchType::create(['name' => 'Singles', 'slug' => 'singles']);
        MatchType::create(['name' => 'Tag Team', 'slug' => 'tagteam']);
        MatchType::create(['name' => 'Triple Threat', 'slug' => 'triple']);
        MatchType::create(['name' => 'Triangle', 'slug' => 'triangle']);
        MatchType::create(['name' => 'Fatal 4 Way', 'slug' => 'fatal4way']);
        MatchType::create(['name' => '6 Tag Team', 'slug' => 'tagteam']);
        MatchType::create(['name' => '8 Tag Team', 'slug' => 'tagteam']);
        MatchType::create(['name' => '10 Tag Team', 'slug' => 'tagteam']);
        MatchType::create(['name' => 'Two On One Handicap Match', 'slug' => '21handicap']);
        MatchType::create(['name' => 'Three On Two Handicap Match', 'slug' => '32handicap']);
        MatchType::create(['name' => 'Battle Royal Match', 'slug' => 'battleroyal']);
        MatchType::create(['name' => 'Royal Rumble Match', 'slug' => 'royalrumble']);
        MatchType::create(['name' => 'Tornado Tag Team Match', 'slug' => 'tornadotag']);
        MatchType::create(['name' => 'Gauntlet Match', 'slug' => 'gauntlet']);
    }
}

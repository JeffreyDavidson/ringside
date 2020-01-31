<?php

use App\Models\TagTeam;
use Illuminate\Database\Seeder;

class TagTeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($w = 1; $w <= 50; $w++) {
            $tagTeam = factory(TagTeam::class)->create([
                'name' => 'Tag Team '.$w,
            ]);

            $tagTeam->employ(now()->subYears(1));
        }

        $eNum = 51;
        for ($i = 1; $i <= 15; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                factory(TagTeam::class)->create([
                    'name' => 'Tag Team '. $eNum,
                ]);

                $tagTeam->employ(now()->subYears(1)->addMonth($i));

                $eNum ++;
            }
        }
    }
}

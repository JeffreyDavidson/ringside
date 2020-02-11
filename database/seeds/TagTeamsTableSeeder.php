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
            TagTeamFactory::new()
                ->employed(
                    EmploymentFactory::new()->started(now()->subYears(1))
                )
                ->bookable()
                ->create(['name' => 'Tag Team '.$w]);
        }

        $eNum = 51;
        for ($i = 1; $i <= 15; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                TagTeamFactory::new()
                    ->employed(
                        EmploymentFactory::new()->started(now()->subYears(1)->addMonth($i))
                    )
                    ->create(['name' => 'Tag Team '.$eNum]);

                $eNum++;
            }
        }
    }
}

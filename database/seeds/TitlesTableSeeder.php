<?php

use Illuminate\Database\Seeder;
use Tests\Factories\TitleFactory;

class TitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eNum = 1;

        for ($w = $eNum; $w <= 5; $w++) {
            TitleFactory::new()
                ->active()
                ->create(['name' => 'Title '.$eNum]);

            $eNum ++;
        }

        TitleFactory::new()
            ->pendingActivation()
            ->create(['name' => 'Title '. $eNum]);

        $eNum ++;

        TitleFactory::new()
            ->retired()
            ->create(['name' => 'Title '. $eNum]);

        $eNum ++;

        TitleFactory::new()
            ->inactive()
            ->create(['name' => 'Title '. $eNum]);

        $eNum ++;

        TitleFactory::new()
            ->unactivated()
            ->create(['name' => 'Title '. $eNum]);
    }
}

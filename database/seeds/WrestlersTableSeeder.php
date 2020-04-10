<?php

use Illuminate\Database\Seeder;
use Tests\Factories\EmploymentFactory;
use Tests\Factories\WrestlerFactory;

class WrestlersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($w = 1; $w <= 50; $w++) {
            WrestlerFactory::new()
                ->employed(
                    EmploymentFactory::new()->started(now()->subYears(1))
                )
                ->bookable()
                ->create(['name' => 'Wrestler '.$w]);
        }

        $eNum = 51;
        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                WrestlerFactory::new()
                ->pendingEmployment(
                    EmploymentFactory::new()->started(now()->subYears(1)->addMonth($i))
                )
                ->create(['name' => 'Wrestler '.$eNum]);

                $eNum ++;
            }
        }

        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                WrestlerFactory::new()
                    ->pendingEmployment(
                        EmploymentFactory::new()->started(now()->addMonth($i))
                    )
                    ->create(['name' => 'Wrestler '.$eNum]);

                $eNum ++;
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            WrestlerFactory::new()
                ->unemployed()
                ->create(['name' => 'Wrestler '.$eNum]);

            $eNum ++;
        }
    }
}

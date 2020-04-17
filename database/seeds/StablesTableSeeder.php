<?php

use Illuminate\Database\Seeder;
use Tests\Factories\StableFactory;
use Tests\Factories\EmploymentFactory;

class StablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eNum = 1;


        /** Initially create 3 bookable stables started a year ago. */
        for ($w = 1; $w <= 3; $w++) {
            StableFactory::new()
                ->employed(
                    EmploymentFactory::new()->started(now()->subYears(1))
                )
                ->bookable()
                ->create(['name' => 'Stable '.$eNum]);

                $eNum ++;
        }

        /**
         * For each month of the previous year create
         * one new stables that are bookable.
         */
        for ($i = 1; ($i + 4) <= 12; $i++) {
            for ($j = 1; $j <= 1; $j++) {
                StableFactory::new()
                    ->pendingEmployment(
                        EmploymentFactory::new()->started(now()->subYears(1)->addMonth($i + 4))
                    )
                    ->create(['name' => 'Tag Team '.$eNum]);

                $eNum ++;
            }
        }

        /**
         * Create two new pending employment stables every 3
         * months starting from the current date.
         */

        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 2; $j++) {
                StableFactory::new()
                    ->pendingEmployment(
                        EmploymentFactory::new()->started(now()->addMonth($i))
                    )
                    ->create(['name' => 'Tag Team '.$eNum]);

                $eNum ++;
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            StableFactory::new()
                ->unemployed()
                ->create(['name' => 'Tag Team '.$eNum]);

            $eNum ++;
        }
    }
}

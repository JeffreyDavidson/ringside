<?php

use Illuminate\Database\Seeder;
use Tests\Factories\ActivationFactory;
use Tests\Factories\StableFactory;

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

        /** Initially create 3 active stables started a year ago. */
        for ($w = 1; $w <= 3; $w++) {
            StableFactory::new()
                ->active()
                ->create(['name' => 'Stable '.$eNum]);

                $eNum ++;
        }

        /**
         * For each month of the previous year create
         * one new stables that are bookable.
         */
        StableFactory::new()
            ->pendingActivation(
                ActivationFactory::new()->started(now()->addMonth(1))
            )
            ->create(['name' => 'Stable '.$eNum]);

        $eNum ++;

        StableFactory::new()
            ->inactive(
                ActivationFactory::new()->started(now()->subMonths(4))
            )
            ->create(['name' => 'Stable '.$eNum]);

        $eNum ++;
    }
}

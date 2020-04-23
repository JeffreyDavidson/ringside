<?php

use Illuminate\Database\Seeder;
use Tests\Factories\WrestlerFactory;
use Tests\Factories\EmploymentFactory;
use Tests\Factories\RetirementFactory;

class WrestlersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eNum = 1;

        /**
         * We need to create 30 wrestlers at this time 20 years ago but since by
         * the time we reach the current date these wrestlers should be
         * released so we need to make them released and figure out
         * their started and ended employment date.
         */
        for ($j = 1; $j <= 30; $j++) {
            WrestlerFactory::new()
                ->released(
                    EmploymentFactory::new()
                        ->started(
                            now()->subYears(20)
                        )
                        ->ended(
                            now()->subYears(rand(8, 20))
                        )
                )
                ->create(['name' => 'Wrestler '.$eNum]);

            $eNum ++;
        }

        /**
         * We need to create 5 wrestlers at this time 20 years ago for each
         * additional month but since by the time we reach the current
         * date these wrestlers should be released so we need to
         * make them released and figure out their started
         * and ended employment date.
         */
        for ($i = 1; $i <= 12*20; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                WrestlerFactory::new()
                    ->released(
                        EmploymentFactory::new()
                            ->started(
                                now()->subYears(20)->addMonth($i)
                            )
                            ->ended(
                                now()->subYears(rand(8, 20))
                            )
                    )
                    ->create(['name' => 'Wrestler '.$eNum]);

                $eNum ++;
            }
        }

        /**
         * We need to create 30 wrestlers at this same time 1 year ago and all
         * wrestlers should be bookable and should NOT have a ended
         * employment date.
         */
        for ($w = $eNum; $w <= 30; $w++) {
            WrestlerFactory::new()
                ->bookable(EmploymentFactory::new()->started(now()->subYears(1)))
                ->create(['name' => 'Wrestler '.$w]);

            $eNum ++;
        }

        /**
         * We need to create 5 wrestlers at this same time 1 year ago starting
         * with the next month wrestlers should be bookable and should
         * NOT have an ended employment date.
         */
        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                WrestlerFactory::new()
                    ->bookable(
                        EmploymentFactory::new()->started(now()->subYears(1)->addMonth($i))
                    )
                    ->create(['name' => 'Wrestler '.$eNum]);

                $eNum ++;
            }
        }

        /**
         * We need to create 5 wrestlers at this same time and 5 more for 3
         * additional months and all wrestlers should be Pending
         * Employment and should NOT have a ended employment
         * date.
         */
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

        /**
         * We need to create 5 wrestlers that have been created but do not
         * have an employment date. These wrestlers should be marked as
         * being Unemployed.
         */
        for ($i = 1; $i <= 10; $i++) {
            WrestlerFactory::new()
                ->unemployed()
                ->create(['name' => 'Wrestler '.$eNum]);

            $eNum ++;
        }

        /**
         * We need to create 10 wrestlers that have been retired. We need to
         * make sure that their employment end date is the same as their
         * start of their retirement date.
         */
        for ($i = 1; $i <= 10; $i++) {
            WrestlerFactory::new()
                ->retired(
                    RetirementFactory::new()->started(),
                    EmploymentFactory::new()->started()->ended()
                )
                ->create(['name' => 'Wrestler '.$eNum]);

            $eNum ++;
        }

    }
}

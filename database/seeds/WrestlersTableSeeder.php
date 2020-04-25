<?php

use Carbon\Carbon;
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
    public function run($dateToStart = null)
    {
        $eNum = 1;
        $dateToStart = Carbon::now()->subYears(5);

        $diffInYears = $dateToStart->diffInYears(now());
        $minYears = ceil($diffInYears*.25);
        $maxYears = floor($diffInYears*.75);
        $randomNumberOfYearsEmployed = rand($minYears, $maxYears);

        /**
         * We need to create 30 wrestlers at this time X years ago but since by
         * the time we reach the current date these wrestlers should be
         * released so we need to make them released and figure out
         * their started and ended employment date.
         */
        for ($j = 1; $j <= 30; $j++) {
            $started = $dateToStart;
            $ended = now()->subYears($randomNumberOfYearsEmployed)->subMonths(rand(1, 11));
            WrestlerFactory::new()
                ->released(
                    EmploymentFactory::new()
                        ->started($started)
                        ->ended($ended)
                )
                ->create(['name' => 'Wrestler '.$eNum]);

            $eNum ++;
        }

        /**
         * We need to create 5 wrestlers at this time x years ago for each
         * additional month but since by the time we reach the current
         * date these wrestlers should be released so we need to
         * make them released and figure out their started
         * and ended employment date.
         * FIXME: Please!
         */
        for ($i = 1; $i <= 12*$diffInYears; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $started =  $dateToStart->copy()->addMonth($i);
                $ended =  $started->copy()->addMonth(rand(1, 11));
                WrestlerFactory::new()
                    ->released(
                        EmploymentFactory::new()
                         ->started($started)
                         ->ended($ended)
                    )
                    ->create(['name' => 'Wrestler '.$eNum]);

                $eNum ++;
            }
        }

        /**
         * We need to create 5 wrestlers at this same time 1 year ago starting
         * with the next month wrestlers should be bookable and should
         * NOT have an ended employment date.
         */
        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $started = now()->subYears(1)->addMonth($i);
                WrestlerFactory::new()
                    ->bookable(
                        EmploymentFactory::new()->started($started)
                    )
                    ->create(['name' => 'Wrestler '.$eNum]);

                $eNum ++;
            }
        }

        /**
         * We need to create 5 wrestlers for the next 3 months and all
         * wrestlers should be Pending Employment and should NOT
         * have an ended employment date.
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
         * We need to create 5 wrestlers that do not have an employment date.
         * These wrestlers should be marked as being Unemployed.
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
         * FIXME: for staging purposes
         */
        // for ($i = 1; $i <= 10; $i++) {
        //     WrestlerFactory::new()
        //         ->retired(
        //             RetirementFactory::new()->started(),
        //             EmploymentFactory::new()->started()->ended()
        //         )
        //         ->create(['name' => 'Wrestler '.$eNum]);

        //     $eNum ++;
        // }
    }
}

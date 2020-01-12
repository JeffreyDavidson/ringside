<?php

use App\Models\Manager;
use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($w = 1; $w <= 20; $w++) {
            $manager = factory(Manager::class)->create([
                'first_name' => 'Manager',
                'last_name' => $w,
            ]);

            $manager->employ(now()->subYears(1));
        }

        $eNum = 21;
        for ($i = 1; $i <= 15; $i++) {
            for ($j = 1; $j <= 2; $j++) {
                $manager = factory(Manager::class)->create([
                    'first_name' => 'Manager',
                    'last_name' => $eNum,
                ]);

                $manager->employ(now()->subYear(1)->addMonth($i));

                $eNum ++;
            }
        }
    }
}

<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     *
     * @test
     */
    public function basic_example()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Laravel');
        });
    }
}

<?php

namespace Tests\Integration\Livewire\Titles;

use App\Http\Livewire\Titles\FutureActivationAndUnactivatedTitles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

class FutureActivationAndUnactivatedTitlesTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $titles;

    protected function setUp(): void
    {
        parent::setUp();

        $active = TitleFactory::new()->count(3)->active()->create();
        $futureActivation = TitleFactory::new()->count(3)->futureActivation()->create();
        $unactivated = TitleFactory::new()->count(3)->unactivated()->create();
        $inactive = TitleFactory::new()->count(3)->inactive()->create();
        $retired = TitleFactory::new()->count(3)->retired()->create();

        $this->titles = collect([
            'active'            => $active,
            'future-activation' => $futureActivation,
            'unactivated'       => $unactivated,
            'inactive'          => $inactive,
            'retired'           => $retired,
            'all'               => collect()
                                    ->concat($active)
                                    ->concat($futureActivation)
                                    ->concat($unactivated)
                                    ->concat($inactive)
                                    ->concat($retired),
        ]);
    }

    /** @test */
    public function the_one_where()
    {
        $component = Livewire::test(FutureActivationAndUnactivatedTitles::class);

        $this->assertEquals(
            'livewire.titles.future-activation-and-unactivated-titles',
            $component->lastRenderedView->getName()
        );
        $component->assertViewHas('futureActivationAndUnactivatedTitles');
    }
}

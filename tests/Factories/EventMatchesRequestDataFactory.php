<?php

namespace Tests\Factories;

use App\Models\Event;
use App\Models\MatchType;
use App\Models\Referee;

class EventMatchesRequestDataFactory
{
    private int $match_type_id = 1;
    private int $referee_id = 1;
    private ?int $title_id = null;
    private array $competitors = [1, 2];
    private ?string $preview = null;

    public function __construct()
    {
        $this->match_type_id = MatchType::first()->id;
        $this->referee_id = Referee::factory()->create()->id;
    }

    public static function new(): self
    {
        return new self();
    }

    public function create(array $overrides = []): array
    {
        return array_replace([
            'match_type_id' => $this->match_type_id,
            'referee_id' => $this->referee_id,
            'title_id' => $this->title_id,
            'competitors' => $this->competitors,
            'preview' => $this->preview,
        ], $overrides);
    }
}

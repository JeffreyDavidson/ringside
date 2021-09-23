<?php

namespace Tests\Factories;

use App\Models\MatchType;
use App\Models\Referee;

class EventMatchesRequestDataFactory
{
    private const DEFAULT_MATCH_TYPE = 1;
    private const DEFAULT_REFEREE_ID = [1];
    private const DEFAULT_COMPETITORS = [1, 2];

    public function __construct()
    {
        $this->match_type_id = MatchType::first()->id ?? self::DEFAULT_MATCH_TYPE;
        $this->referees = [Referee::factory()->create()->id] ?? self::DEFAULT_REFEREE_ID;
        $this->titles = null;
        $this->competitors = self::DEFAULT_COMPETITORS;
        $this->preview = null;
        $this->matches = [
            [
                'match_type_id' => $this->match_type_id,
                'referees'    => $this->referees,
                'titles'      => $this->titles,
                'competitors'   => $this->competitors,
                'preview'       => $this->preview,
            ],
        ];
    }

    public static function new(): self
    {
        return new self();
    }

    public function create(array $matches = []): array
    {
        return array_replace_recursive([
            'matches' => [
                0 => [
                    'match_type_id' => $this->match_type_id,
                    'referees'      => $this->referees,
                    'titles'        => $this->titles,
                    'competitors'   => $this->competitors,
                    'preview'       => $this->preview,
                ],
            ],
        ], $matches);
    }
}

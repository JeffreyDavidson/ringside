<?php

namespace App\DataTransferObjects;

use App\Http\Requests\EventMatches\StoreRequest;
use App\Models\MatchType;
use App\Models\Referee;
use App\Models\TagTeam;
use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\Support\Collection;

class EventMatchData
{
    public MatchType $matchType;
    public Collection $referees;
    public ?Collection $titles;
    public Collection $competitors;
    public Collection $wrestlers;
    public Collection $tagTeams;
    public ?string $preview;

    /**
     * Retrieve data from the store request.
     *
     * @param  \App\Http\Requests\EventMatches\StoreRequest $request
     *
     * @return self
     */
    public static function fromStoreRequest(StoreRequest $request): self
    {
        $dto = new self;

        $dto->matchType = MatchType::whereKey($request->input('match_type_id'))->sole();
        $dto->referees = Referee::findMany($request->input('referees'));
        $dto->titles = Title::findMany($request->input('titles'));
        $dto->competitors = self::getCompetitors($request->input('competitors'));
        $dto->preview = $request->input('preview');

        return $dto;
    }

    /**
     * Undocumented function.
     *
     * @param  array $competitors
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getCompetitors(array $competitors)
    {
        $formattedCompetitors = collect();

        foreach ($competitors as $competitor) {
            $wrestlers = collect();
            $tagTeams = collect();
            if ($competitor['competitor_type'] == 'wrestler') {
                $wrestler = Wrestler::find($competitor['competitor_id']);
                $formattedCompetitors->push(['wrestlers' => collect($wrestlers->push($wrestler))]);
            } elseif ($competitor['competitor_type'] == 'tag_team') {
                $tagTeam = TagTeam::find($competitor['competitor_id']);
                $formattedCompetitors->push(['tag_teams' => collect($tagTeams->push($tagTeam))]);
            }
        }

        return $formattedCompetitors;
    }

    /**
     * Retrieve the competitors that are wrestlers.
     *
     * @param  array $competitors
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getWrestlers($competitors)
    {
        $wrestlers = array_filter(
            $competitors,
            static fn ($contestant) => $contestant['competitor_type'] === 'wrestler'
        );

        $wrestler_ids = array_column($wrestlers, 'competitor_id');

        return Wrestler::findMany($wrestler_ids);
    }

    /**
     * Retrieve the competitors that are tag teams.
     *
     * @param  array $competitors
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTagTeams($competitors)
    {
        $tagTeams = array_filter(
            $competitors,
            static fn ($contestant) => $contestant['competitor_type'] === 'tag_teams'
        );

        $tag_team_ids = array_column($tagTeams, 'competitor_id');

        return TagTeam::findMany($tag_team_ids);
    }
}

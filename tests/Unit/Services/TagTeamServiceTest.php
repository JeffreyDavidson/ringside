<?php

namespace Tests\Unit\Services;

use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Services\TagTeamService;
use Tests\TestCase;

/**
 * @group tagteams
 * @group roster
 * @group services
 */
class TagTeamServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_tag_team_and_tag_team_partners_with_an_employment()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($tagTeam);
        $repositoryMock->expects()->addWrestlers($tagTeamMock, $data['wrestlers'], $data['started_at'])->once();

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_tag_team_and_tag_team_partners_without_an_employment()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($tagTeamMock);
        $repositoryMock->expects()->addWrestlers($tagTeamMock, $data['wrestlers'])->once();

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_tag_team_without_tag_team_partners_and_without_an_employment()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($tagTeamMock);
        $repositoryMock->shouldNotHaveReceived('addWrestlers');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_tag_team_and_employ_without_an_employment_start_date()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->update($tagTeamMock, $data)->once()->andReturns($tagTeamMock);
        // Expect a call to not be made to employOrUpdateEmployment

        $service->update($tagTeamMock, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_future_employed_tag_team_and_employ_if_started_at_is_filled()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->update($tagTeamMock, $data)->once()->andReturns($tagTeamMock);
        $repositoryMock->expects()->addWrestlers($tagTeamMock, $data['wrestlers'])->once();

        $service->update($tagTeamMock, $data);
    }

    /**
     * @test
     */
    public function it_can_employ_a_tag_team_that_is_not_in_employment()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $service->employOrUpdateEmployment($tagTeamMock, now()->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_can_update_a_tag_team_employment_date_when_tag_team_has_future_employment()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $service->employOrUpdateEmployment($tagTeamMock, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_tag_team()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->delete($tagTeamMock)->once();

        $service->delete($tagTeamMock);
    }

    /**
     * @test
     */
    public function it_can_restore_a_tag_team()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->restore($tagTeamMock)->once();

        $service->restore($tagTeamMock);
    }
}

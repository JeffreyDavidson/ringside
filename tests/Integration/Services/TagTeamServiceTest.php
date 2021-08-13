<?php

namespace Tests\Integration\Services;

use App\Models\Employment;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Services\TagTeamService;
use App\Strategies\Employment\TagTeamEmploymentStrategy;
use App\Strategies\Reinstate\TagTeamReinstateStrategy;
use App\Strategies\Release\TagTeamReleaseStrategy;
use App\Strategies\Retirement\TagTeamRetirementStrategy;
use App\Strategies\Suspend\TagTeamSuspendStrategy;
use App\Strategies\Unretire\TagTeamUnretireStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 * @group services
 */
class TagTeamServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_tag_team_and_tag_team_partners_with_an_employment()
    {
        $data = [
            'name' => 'Example Tag Team',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
            'wrestlers' => [1, 2],
        ];
        $tagTeam = TagTeam::factory()->make(['name' => 'Example Tag Team']);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategyMock = $this->mock(TagTeamEmploymentStrategy::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($tagTeam);
        $strategyMock->expects()->setEmployable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ($employmentDate)->once()->andReturns($strategyMock);
        $repositoryMock->expects()->addWrestlers($tagTeam, $data['wrestlers'], $data['started_at'])->once();

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_tag_team_and_tag_team_partners_without_an_employment()
    {
        $data = [
            'name' => 'Example Tag Team',
            'wrestlers' => [1, 2],
        ];
        $tagTeam = TagTeam::factory()->make(['name' => 'Example Tag Team']);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategyMock = $this->mock(TagTeamEmploymentStrategy::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($tagTeam);
        $repositoryMock->expects()->addWrestlers($tagTeam, $data['wrestlers'])->once();

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_tag_team_without_tag_team_partners_and_without_an_employment()
    {
        $data = [
            'name' => 'Example Tag Team',
        ];
        $tagTeam = TagTeam::factory()->make(['name' => 'Example Tag Team']);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategyMock = $this->mock(TagTeamEmploymentStrategy::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($tagTeam);
        $strategyMock->shouldNotHaveReceived('setEmployable');
        $strategyMock->shouldNotHaveReceived('employ');
        $repositoryMock->shouldNotHaveReceived('addWrestlers');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_tag_team_and_employ_without_an_employment_start_date()
    {
        $data = [
            'name' => 'Example Tag Team',
        ];
        $tagTeam = TagTeam::factory()->make();
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->update($tagTeam, $data)->once()->andReturns($tagTeam);
        // Expect a call to not be made to employOrUpdateEmployment

        $service->update($tagTeam, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_future_employed_tag_team_and_employ_if_started_at_is_filled()
    {
        $data = [
            'name' => 'Tag Team Example',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
            'wrestlers' => [1, 2],
        ];
        $tagTeam = TagTeam::factory()->make();
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->update($tagTeam, $data)->once()->andReturns($tagTeam);
        $repositoryMock->expects()->addWrestlers($tagTeam, $data['wrestlers'])->once();

        $service->update($tagTeam, $data);
    }

    /**
     * @test
     */
    public function it_can_employ_a_tag_team_that_is_not_in_employment()
    {
        $tagTeam = TagTeam::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $serviceMock = $this->mock(TagTeamEmploymentStrategy::class);
        $service = new TagTeamService($repositoryMock);

        $serviceMock->expects()->setEmployable($tagTeam)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($tagTeam, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_update_a_tag_team_employment_date_when_tag_team_has_future_employment()
    {
        $tagTeam = TagTeam::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $serviceMock = $this->mock(TagTeamEmploymentStrategy::class);
        $service = new TagTeamService($repositoryMock);

        $serviceMock->expects()->setEmployable($tagTeam)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($tagTeam, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_tag_team()
    {
        $tagTeam = TagTeam::factory()->make();
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->delete($tagTeam)->once();

        $service->delete($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_restore_a_tag_team()
    {
        $tagTeam = new TagTeam;
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $repositoryMock->expects()->restore($tagTeam)->once();

        $service->restore($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_employ_a_tag_team()
    {
        $tagTeam = new TagTeam();
        $strategyMock = $this->mock(TagTeamEmploymentStrategy::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $strategyMock->expects()->setEmployable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ()->once();

        $service->employ($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_release_a_tag_team()
    {
        $tagTeam = new TagTeam();
        $strategyMock = $this->mock(TagTeamReleaseStrategy::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $strategyMock->expects()->setReleasable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->release()->once();

        $service->release($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_suspend_a_tag_team()
    {
        $tagTeam = new TagTeam();
        $strategyMock = $this->mock(TagTeamSuspendStrategy::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $strategyMock->expects()->setSuspendable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->suspend()->once();

        $service->suspend($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_reinstate_a_tag_team()
    {
        $tagTeam = new TagTeam();
        $strategyMock = $this->mock(TagTeamReinstateStrategy::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $strategyMock->expects()->setReinstatable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->reinstate()->once();

        $service->reinstate($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_retire_a_tag_team()
    {
        $tagTeam = new TagTeam();
        $strategyMock = $this->mock(TagTeamRetirementStrategy::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $strategyMock->expects()->setRetirable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->retire()->once();

        $service->retire($tagTeam);
    }

    /**
     * @test
     */
    public function it_can_unretire_a_tag_team()
    {
        $tagTeam = new TagTeam();
        $strategyMock = $this->mock(TagTeamUnretireStrategy::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $service = new TagTeamService($repositoryMock);

        $strategyMock->expects()->setUnretirable($tagTeam)->once()->andReturns($strategyMock);
        $strategyMock->expects()->unretire()->once();

        $service->unretire($tagTeam);
    }
}

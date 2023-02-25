<?php

namespace App\Tests\Unit\Team\Query;

use App\Domain\Entity\Team;
use App\UseCase\Team\Command\CreateTeam\Request;
use App\UseCase\Team\Query\GetTeams\Request as RequestList;
use App\UseCase\Team\Query\GetTeams\Response as ResponseList;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TeamListTest extends KernelTestCase
{
    public function testTeamsListShouldBeEmpty(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.list');

        $teamListRequest = new RequestList();

        $response = ($useCase)($teamListRequest);

        $this->assertEmpty($response->getTeams());
        $this->assertInstanceOf(ResponseList::class, $response);
    }

    public function testListNoEmpty(): void
    {
        $kernel = self::bootKernel();

        $useCaseCreate = $kernel->getContainer()->get('usecase.team.create');
        $useCaseList = $kernel->getContainer()->get('usecase.team.list');

        // Insert a player
        $teamCreateRequest = new Request('Guillaume RAINERI');
        ($useCaseCreate)($teamCreateRequest);

        $teamListRequest = new RequestList();

        /** @var ResponseList $response */
        $response = ($useCaseList)($teamListRequest);

        $this->assertNotEmpty($response->getTeams());
        $this->assertInstanceOf(Team::class, $response->getTeams()[0]);
    }
}

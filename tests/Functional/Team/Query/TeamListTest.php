<?php

namespace App\Tests\Functional\Team\Query;

use App\Domain\Entity\Team;
use App\UseCase\Team\Query\GetTeams\Request as RequestList;
use App\UseCase\Team\Query\GetTeams\Response as ResponseList;
use App\UseCase\Team\Query\GetTeams\UseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class TeamListTest extends KernelTestCase
{
    private EntityManagerInterface|null $entityManager = null;
    private UseCase|null $useCase = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        /* @var EntityManagerInterface $entityManager */
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->useCase = $kernel->getContainer()->get('usecase.team.list');
    }

    public function testTeamsListShouldBeEmpty(): void
    {
        $cn = $this->entityManager->getConnection();
        $cn->executeQuery('DELETE FROM game');
        $cn->executeQuery('DELETE FROM team');

        $teamListRequest = new RequestList();

        $response = ($this->useCase)($teamListRequest);

        $this->assertEmpty($response->getTeams());
        $this->assertInstanceOf(ResponseList::class, $response);
    }

    public function testListNotEmpty(): void
    {
        $team = new Team(Uuid::v4(), 'name'.rand(0, 100));
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $teamListRequest = new RequestList();

        /** @var ResponseList $response */
        $response = ($this->useCase)($teamListRequest);

        $this->assertNotEmpty($response->getTeams());
        $this->assertInstanceOf(Team::class, $response->getTeams()[0]);
    }
}

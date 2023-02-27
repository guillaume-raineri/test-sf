<?php

namespace App\Tests\Functional\Team\Query;

use App\Domain\Entity\Game;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\UseCase\Team\Query\GetTeamGames\Request;
use App\UseCase\Team\Query\GetTeamGames\Response;
use App\UseCase\Team\Query\GetTeamGames\UseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TeamListGamesTest extends KernelTestCase
{
    private EntityManagerInterface|null $entityManager = null;
    private UseCase|null $useCase = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        /* @var EntityManagerInterface $entityManager */
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->useCase = $kernel->getContainer()->get('usecase.team.game.list');
    }

    /**
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function testTeamGamesListShouldBeEmpty(): void
    {
        $teamListGamesRequest = new Request('15bf71c0-d31e-4ff9-889a-33e80a888c55');

        $response = ($this->useCase)($teamListGamesRequest);

        $this->assertEmpty($response->getGames());
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testTeamGamesListShouldNotBeEmpty(): void
    {
        // Can be find in fixtures
        $teamListGamesRequest = new Request('89f3ba99-ed47-405d-b62b-59fb90fa9095');

        /** @var Response $response */
        $response = ($this->useCase)($teamListGamesRequest);

        $this->assertNotEmpty($response->getGames());
        $this->assertInstanceOf(Game::class, $response->getGames()[0]);
    }

    public function testTeamGamesListShouldThrowNotFoundException(): void
    {
        // Can be find in fixtures
        $teamListGamesRequest = new Request('89f3ba99-ed47-405d-b62b-59fb90fa9094');

        $this->expectException(NotFoundException::class);

        /** @var Response $response */
        $response = ($this->useCase)($teamListGamesRequest);

        $this->assertStringContainsString('Team not found.', $this->getExpectedExceptionMessage());
    }

    public function testTeamGamesListShouldThrowBadRequestValidationException(): void
    {
        // Can be find in fixtures
        $teamListGamesRequest = new Request('bad_uuid');

        $this->expectException(ValidationException::class);

        /** @var Response $response */
        $response = ($this->useCase)($teamListGamesRequest);

        $this->assertStringContainsString('Team not found.', $this->getExpectedExceptionMessage());
    }
}

<?php

namespace App\Tests\Functional\Game\Command;

use App\Domain\Entity\Team;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\UseCase\Game\Command\CreateGame\Request;
use App\UseCase\Game\Command\CreateGame\Response;
use App\UseCase\Game\Command\CreateGame\UseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class GameCreateTest extends KernelTestCase
{
    private Team|null $homeTeam = null;
    private Team|null $awayTeam = null;
    private EntityManagerInterface $em;
    private UseCase|null $useCase = null;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->em = $em;

        $this->homeTeam = new Team(Uuid::v4(), 'RCSA');
        $this->awayTeam = new Team(Uuid::v4(), 'PSG');
        $this->em->persist($this->homeTeam);
        $this->em->persist($this->awayTeam);
        $this->em->flush();

        $this->useCase = static::$kernel->getContainer()->get('usecase.game.create');
    }

    public function testTeamCreationOk(): void
    {
        $createGameRequest = new Request('test game', $this->homeTeam->getId(), $this->awayTeam->getId());

        $response = ($this->useCase)($createGameRequest);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testCreateGameNameTooLong(): void
    {
        $createGameRequest = new Request(
            'm46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR',
            $this->homeTeam->getId(),
            $this->awayTeam->getId()
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name must have less than 255 characters.');

        ($this->useCase)($createGameRequest);
    }

    public function testCreateGameBadUuid(): void
    {
        $createGameRequest = new Request('name', 'bad home uuid', 'bad away uuid');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Bad request.');

        ($this->useCase)($createGameRequest);
    }

    public function testCreateGameSameUuid(): void
    {
        $createGameRequest = new Request('name', 'bad uuid', 'bad uuid');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Can\'t use same team');

        ($this->useCase)($createGameRequest);
    }

    public function testCreateGameHomeTeamNotFound(): void
    {
        $createGameRequest = new Request('name', Uuid::v4(), $this->awayTeam->getId());

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Home team not found.');

        ($this->useCase)($createGameRequest);
    }

    public function testCreateGameAwayTeamNotFound(): void
    {
        $createGameRequest = new Request('name', $this->homeTeam->getId(), Uuid::v4());

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Away team not found.');

        ($this->useCase)($createGameRequest);
    }
}

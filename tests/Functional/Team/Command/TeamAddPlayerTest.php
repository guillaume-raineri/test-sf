<?php

namespace App\Tests\Functional\Team\Command;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\UseCase\Team\Command\AddPlayer\Request;
use App\UseCase\Team\Command\AddPlayer\Response;
use App\UseCase\Team\Command\AddPlayer\UseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class TeamAddPlayerTest extends KernelTestCase
{
    private Team|null $team = null;
    private Player|null $player = null;
    private EntityManagerInterface $em;
    private UseCase|null $useCase = null;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->em = $em;

        $this->team = new Team(Uuid::v4(), 'RCSA');
        $this->player = new Player(Uuid::v4(), 'Guillaume');

        $this->em->persist($this->team);
        $this->em->persist($this->player);
        $this->em->flush();

        $this->useCase = static::$kernel->getContainer()->get('usecase.team.player.add');
    }

    public function testTeamCreationOK(): void
    {
        $teamAddPlayer = new Request($this->team->getId(), $this->player->getId());

        $response = ($this->useCase)($teamAddPlayer);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testAddPlayerBadUuid(): void
    {
        $addPlayerRequest = new Request('bad uuid', 'bad uuid');

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Bad request.');

        ($this->useCase)($addPlayerRequest);
    }

    public function testAddPlayerTeamNotFound(): void
    {
        $addPlayerRequest = new Request(Uuid::v4(), $this->player->getId());

        // Should throw exception
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Team not found.');

        ($this->useCase)($addPlayerRequest);
    }

    public function testAddPlayerPlayerNotFound(): void
    {
        $addPlayerRequest = new Request($this->team->getId(), Uuid::v4());

        // Should throw exception
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Player not found.');

        // Try to insert it again
        ($this->useCase)($addPlayerRequest);
    }
}

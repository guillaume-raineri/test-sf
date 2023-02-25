<?php

namespace App\Tests\Unit\Team\Command;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Domain\Exception\ValidationException;
use App\UseCase\Team\Command\AddPlayer\Request;
use App\UseCase\Team\Command\AddPlayer\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class TeamAddPlayerTest extends KernelTestCase
{
    private Team|null $team = null;
    private Player|null $player = null;
    private EntityManagerInterface $em;

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
    }

    public function testTeamCreationOK(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.player.add');

        $teamAddPlayer = new Request($this->team->getId(), $this->player->getId());

        $response = ($useCase)($teamAddPlayer);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testAddPlayerBadUuid(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.player.add');

        $addPlayerRequest = new Request('bad uuid', 'bad uuid');

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Bad request.');

        ($useCase)($addPlayerRequest);
    }

    public function testAddPlayerTeamNotFound(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.player.add');

        $addPlayerRequest = new Request(Uuid::v4(), $this->player->getId());

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Team not found.');

        ($useCase)($addPlayerRequest);
    }

    public function testAddPlayerPlayerNotFound(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.player.add');

        $addPlayerRequest = new Request($this->team->getId(), Uuid::v4());

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Player not found.');

        // Try to insert it again
        ($useCase)($addPlayerRequest);
    }
}

<?php

namespace App\Tests\UseCase\Team\Command;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Infrastructure\Symfony\Command\Team\Command\AddPlayerCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Uuid;

class AddPlayerTest extends KernelTestCase
{
    private EntityManagerInterface|null $em = null;
    private CommandTester|null $commandTester = null;
    private Team|null $team = null;
    private Player|null $player = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(AddPlayerCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);

        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->team = new Team(Uuid::v4(), 'RCSA');
        $this->player = new Player(Uuid::v4(), 'Guillaume');

        $this->em->persist($this->team);
        $this->em->persist($this->player);
        $this->em->flush();
    }

    public function testAddPlayerToTeamOk(): void
    {
        $r = $this->commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => $this->team->getId(),
            AddPlayerCommand::ARGUMENT_PLAYER_ID => $this->player->getId(),
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testAddPlayerReturnsBadRequestOnBadUuid(): void
    {
        $r = $this->commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => 'bad uuid',
            AddPlayerCommand::ARGUMENT_PLAYER_ID => 'bad_uuid',
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString('Bad request.', $output);

        $this->assertSame(Command::FAILURE, $r);
    }

    public function testAddPlayerTeamNotFound(): void
    {
        $r = $this->commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => Uuid::v4(),
            AddPlayerCommand::ARGUMENT_PLAYER_ID => $this->player->getId(),
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString('Team not found.', $output);

        $this->assertSame(Command::FAILURE, $r);
    }

    public function testAddPlayerPlayerNotFound(): void
    {
        $r = $this->commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => $this->team->getId(),
            AddPlayerCommand::ARGUMENT_PLAYER_ID => Uuid::v4(),
        ]);
        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString('Player not found.', $output);

        $this->assertSame(Command::FAILURE, $r);
    }
}

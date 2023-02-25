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
    public function testAddPlayerToTeamOK(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $team = new Team(Uuid::v4(), 'RCSA');
        $player = new Player(Uuid::v4(), 'Guillaume');

        $entityManager->persist($team);
        $entityManager->persist($player);
        $entityManager->flush();

        $command = $application->find(AddPlayerCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => $team->getId(),
            AddPlayerCommand::ARGUMENT_PLAYER_ID => $player->getId(),
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testAddPlayerReturnsBadRequestOnBadUuid(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(AddPlayerCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => 'bad uuid',
            AddPlayerCommand::ARGUMENT_PLAYER_ID => 'bad_uuid',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Bad request.', $output);

        $this->assertSame(Command::FAILURE, $r);
    }

    public function testAddPlayerTeamNotFound(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $team = new Team(Uuid::v4(), 'RCSA');
        $player = new Player(Uuid::v4(), 'Guillaume');

        $entityManager->persist($team);
        $entityManager->persist($player);
        $entityManager->flush();

        $command = $application->find(AddPlayerCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => Uuid::v4(),
            AddPlayerCommand::ARGUMENT_PLAYER_ID => $player->getId(),
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Team not found.', $output);

        $this->assertSame(Command::FAILURE, $r);
    }

    public function testAddPlayerPlayerNotFound(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $team = new Team(Uuid::v4(), 'RCSA');
        $player = new Player(Uuid::v4(), 'Guillaume');

        $entityManager->persist($team);
        $entityManager->persist($player);
        $entityManager->flush();

        $command = $application->find(AddPlayerCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            AddPlayerCommand::ARGUMENT_TEAM_ID => $team->getId(),
            AddPlayerCommand::ARGUMENT_PLAYER_ID => Uuid::v4(),
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Player not found.', $output);

        $this->assertSame(Command::FAILURE, $r);
    }
}

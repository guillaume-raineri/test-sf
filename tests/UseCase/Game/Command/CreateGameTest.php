<?php

namespace App\Tests\UseCase\Game\Command;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Infrastructure\Symfony\Command\Game\Command\CreateGameCommand;
use App\Infrastructure\Symfony\Command\Player\Command\CreatePlayerCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Uuid;

class CreateGameTest extends KernelTestCase
{
    public function testCreateGameOK(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $homeTeam = new Team(Uuid::v4(), 'RCSA');
        $awayTeam = new Team(Uuid::v4(), 'PSG');

        $entityManager->persist($homeTeam);
        $entityManager->persist($awayTeam);
        $entityManager->flush();

        $command = $application->find(CreateGameCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $awayTeam->getId(),
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testNotWorkingWithMoreThan255Characters(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $homeTeam = new Team(Uuid::v4(), 'RCSA');
        $awayTeam = new Team(Uuid::v4(), 'PSG');

        $entityManager->persist($homeTeam);
        $entityManager->persist($awayTeam);
        $entityManager->flush();

        $command = $application->find(CreateGameCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'm46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $awayTeam->getId(),
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Name must have less than 255', $commandTester->getDisplay());
    }

    public function testCreateGameTeamIdentical(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $homeTeam = new Team(Uuid::v4(), 'RCSA');
        $awayTeam = new Team(Uuid::v4(), 'PSG');

        $entityManager->persist($homeTeam);
        $entityManager->persist($awayTeam);
        $entityManager->flush();

        $command = $application->find(CreateGameCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => 'a9480f69-33ce-466a-a197-9686d75b7f53',
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => 'a9480f69-33ce-466a-a197-9686d75b7f53',
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Can\'t use same team', $commandTester->getDisplay());
    }

    public function testCreateGameHomeTeamNotFound(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $homeTeam = new Team(Uuid::v4(), 'RCSA');
        $awayTeam = new Team(Uuid::v4(), 'PSG');

        $entityManager->persist($homeTeam);
        $entityManager->persist($awayTeam);
        $entityManager->flush();

        $command = $application->find(CreateGameCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => 'a9480f69-33ce-466a-a197-9686d75b7f53',
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $awayTeam->getId(),
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Home team not found', $commandTester->getDisplay());
    }

    public function testCreateGameAwayTeamNotFound(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $homeTeam = new Team(Uuid::v4(), 'RCSA');
        $awayTeam = new Team(Uuid::v4(), 'PSG');

        $entityManager->persist($homeTeam);
        $entityManager->persist($awayTeam);
        $entityManager->flush();

        $command = $application->find(CreateGameCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => 'a9480f69-33ce-466a-a197-9686d75b7f53',
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Away team not found', $commandTester->getDisplay());
    }
}

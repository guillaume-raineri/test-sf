<?php

namespace App\Tests\UseCase\Game\Command;

use App\Domain\Entity\Team;
use App\Infrastructure\Symfony\Command\Game\Command\CreateGameCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Uuid;

class CreateGameTest extends KernelTestCase
{
    private EntityManagerInterface|null $em = null;
    private CommandTester|null $commandTester = null;
    private Team|null $homeTeam = null;
    private Team|null $awayTeam = null;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $application = new Application(static::$kernel);
        $command = $application->find(CreateGameCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->em = $em;

        $this->homeTeam = new Team(Uuid::v4(), 'RCSA');
        $this->awayTeam = new Team(Uuid::v4(), 'PSG');

        $this->em->persist($this->homeTeam);
        $this->em->persist($this->awayTeam);
        $this->em->flush();
    }

    public function testCreateGameOk(): void
    {
        $r = $this->commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'game name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $this->homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $this->awayTeam->getId(),
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testNotWorkingWithMoreThan255Characters(): void
    {
        $r = $this->commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'm46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $this->homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $this->awayTeam->getId(),
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Name must have less than 255', $this->commandTester->getDisplay());
    }

    public function testCreateGameTeamIdentical(): void
    {
        $r = $this->commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $this->homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $this->homeTeam->getId(),
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Can\'t use same team', $this->commandTester->getDisplay());
    }

    public function testCreateGameHomeTeamNotFound(): void
    {
        $r = $this->commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => 'a9480f69-33ce-466a-a197-9686d75b7f53',
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => $this->awayTeam->getId(),
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Home team not found', $this->commandTester->getDisplay());
    }

    public function testCreateGameAwayTeamNotFound(): void
    {
        $r = $this->commandTester->execute([
            CreateGameCommand::ARGUMENT_GAME_NAME => 'name',
            CreateGameCommand::ARGUMENT_GAME_HOME_TEAM_ID => $this->homeTeam->getId(),
            CreateGameCommand::ARGUMENT_GAME_AWAY_TEAM_ID => 'a9480f69-33ce-466a-a197-9686d75b7f53',
        ]);

        $this->assertSame(Command::FAILURE, $r);
        $this->assertStringContainsString('Away team not found', $this->commandTester->getDisplay());
    }
}

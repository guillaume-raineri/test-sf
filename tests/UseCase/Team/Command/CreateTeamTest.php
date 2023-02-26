<?php

namespace App\Tests\UseCase\Team\Command;

use App\Domain\Entity\Team;
use App\Infrastructure\Symfony\Command\Team\Command\CreateTeamCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Uuid;

class CreateTeamTest extends KernelTestCase
{
    private EntityManagerInterface|null $em = null;
    private CommandTester|null $commandTester = null;
    private Team|null $team = null;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $application = new Application(static::$kernel);
        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->em = $em;

        $this->team = new Team(Uuid::v4(), 'RCSA');

        $this->em->persist($this->team);
        $this->em->flush();
    }

    public function testCreateTeamOk(): void
    {
        $r = $this->commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'PSG',
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testNotWorkingWithMoreThan255Characters(): void
    {
        $r = $this->commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'm46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR',
        ]);
        $this->assertSame(Command::FAILURE, $r);
    }

    public function testTeamNameAlreadyExists(): void
    {
        $r = $this->commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'RCSA',
        ]);

        // Assert that the team already exists
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('This team name is already used', $output);

        $this->assertSame(Command::FAILURE, $r);
    }
}

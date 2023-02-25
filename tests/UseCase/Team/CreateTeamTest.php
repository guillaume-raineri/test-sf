<?php

namespace App\Tests\UseCase\Team;

use App\Infrastructure\Symfony\Command\CreateTeamCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CreateTeamTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'RCSA',
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testNotWorkingWithMoreThan255Characters(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'm46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR',
        ]);
        $this->assertSame(Command::FAILURE, $r);
    }

    public function testTeamNameAlreadyExists(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        // Insert it once
        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'RCSA',
        ]);

        // Insert it twice
        $r = $commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'RCSA',
        ]);

        $output = $commandTester->getDisplay();

        // Assert that the team already exists
        $this->assertStringContainsString('This team name is already used', $output);

        $this->assertSame(Command::FAILURE, $r);
    }
}

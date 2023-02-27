<?php

namespace App\Tests\UseCase\Game\Query;

use App\Domain\Entity\Team;
use App\Infrastructure\Symfony\Command\Game\Query\GetGamesCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Uuid;

class GetGamesTest extends KernelTestCase
{
    private EntityManagerInterface|null $entityManager = null;
    private CommandTester|null $commandTester = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(GetGamesCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testListGameAll(): void
    {
        $r = $this->commandTester->execute([]);

        $this->assertStringContainsString('Found 3 games.', $this->commandTester->getDisplay());
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testListGamesSpecificTeamWithResults(): void
    {
        $team = $this->entityManager
            ->getRepository(Team::class)
            ->findOneBy(['name' => 'Real Madrid'])
        ;

        $r = $this->commandTester->execute([
            GetGamesCommand::ARGUMENT_TEAM_ID => $team->getId(),
        ]);

        $this->assertStringContainsString('Found 2 games.', $this->commandTester->getDisplay());
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testListGamesSpecificTeamWithoutResult(): void
    {
        $team = $this->entityManager
            ->getRepository(Team::class)
            ->findOneBy(['name' => 'Bayern Munich'])
        ;

        $r = $this->commandTester->execute([
            GetGamesCommand::ARGUMENT_TEAM_ID => $team->getId(),
        ]);

        $this->assertStringContainsString('Found 0 games.', $this->commandTester->getDisplay());
        $this->assertSame(Command::SUCCESS, $r);
    }

    public function testListGamesFailureTeamNotFound(): void
    {
        $r = $this->commandTester->execute([
            GetGamesCommand::ARGUMENT_TEAM_ID => Uuid::v4(),
        ]);

        $this->assertStringContainsString('Team not found.', $this->commandTester->getDisplay());
        $this->assertSame(Command::FAILURE, $r);
    }
}

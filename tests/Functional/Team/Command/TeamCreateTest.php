<?php

namespace App\Tests\Functional\Team\Command;

use App\Domain\Entity\Team;
use App\Domain\Exception\ValidationException;
use App\UseCase\Team\Command\CreateTeam\Request;
use App\UseCase\Team\Command\CreateTeam\Response;
use App\UseCase\Team\Command\CreateTeam\UseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class TeamCreateTest extends KernelTestCase
{
    private UseCase|null $useCase = null;
    private EntityManagerInterface|null $em = null;
    private Team|null $team = null;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->em = $em;

        $this->team = new Team(Uuid::v4(), 'RCSA');

        $this->em->persist($this->team);
        $this->em->flush();

        $this->useCase = static::$kernel->getContainer()->get('usecase.team.create');
    }

    public function testTeamCreationOK(): void
    {
        $teamCreateRequest = new Request('PSG');

        $response = ($this->useCase)($teamCreateRequest);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testTeamNameTooLong(): void
    {
        $teamCreateRequest = new Request('m46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR');

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name must have less than 255 characters.');

        ($this->useCase)($teamCreateRequest);
    }

    public function testTeamNameAlreadyUsed(): void
    {
        $teamCreateRequest = new Request('RCSA');

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This team name is already used');

        // Try to insert it again
        ($this->useCase)($teamCreateRequest);
    }
}

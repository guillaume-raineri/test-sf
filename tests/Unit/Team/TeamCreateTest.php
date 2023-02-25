<?php

namespace App\Tests\Unit\Team;

use App\Domain\Exception\ValidationException;
use App\UseCase\Team\Command\CreateTeam\Request;
use App\UseCase\Team\Command\CreateTeam\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TeamCreateTest extends KernelTestCase
{
    public function testTeamCreationOK(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.create');

        $teamCreateRequest = new Request('RCSA');

        $response = ($useCase)($teamCreateRequest);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testTeamNameTooLong(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.create');

        $teamCreateRequest = new Request('m46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR');

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name must have less than 255 characters.');

        ($useCase)($teamCreateRequest);
    }

    public function testTeamNameAlreadyUsed(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.team.create');

        $teamCreateRequest = new Request('RCSA');

        // Insert it once
        ($useCase)($teamCreateRequest);

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This team name is already used');

        // Try to insert it again
        ($useCase)($teamCreateRequest);
    }
}

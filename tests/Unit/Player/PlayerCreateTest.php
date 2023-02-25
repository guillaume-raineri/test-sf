<?php

namespace App\Tests\Unit\Player;

use App\Domain\Exception\ValidationException;
use App\UseCase\Player\CreatePlayer\Request;
use App\UseCase\Player\CreatePlayer\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlayerCreateTest extends KernelTestCase
{
    public function testPlayerCreationOK(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.player.create');

        $playerCreateRequest = new Request('Guillaume RAINERI');

        $response = ($useCase)($playerCreateRequest);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testTeamNameTooLong(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.player.create');

        $teamCreateRequest = new Request('m46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR');

        // Should throw exception
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name must have less than 255 characters.');

        ($useCase)($teamCreateRequest);
    }
}

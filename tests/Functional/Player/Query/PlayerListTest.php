<?php

namespace App\Tests\Functional\Player\Query;

use App\Domain\Entity\Player;
use App\UseCase\Player\Command\CreatePlayer\Request;
use App\UseCase\Player\Query\GetPlayers\Request as RequestList;
use App\UseCase\Player\Query\GetPlayers\Response as ResponseList;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlayerListTest extends KernelTestCase
{
    public function testPlayersListShouldBeEmpty(): void
    {
        $kernel = self::bootKernel();

        $useCase = $kernel->getContainer()->get('usecase.player.list');

        $playerListRequest = new RequestList();

        $response = ($useCase)($playerListRequest);

        $this->assertEmpty($response->getPlayers());
        $this->assertInstanceOf(ResponseList::class, $response);
    }

    public function testListNoEmpty(): void
    {
        $kernel = self::bootKernel();

        $useCaseCreate = $kernel->getContainer()->get('usecase.player.create');
        $useCaseList = $kernel->getContainer()->get('usecase.player.list');

        // Insert a player
        $teamCreateRequest = new Request('Guillaume RAINERI');
        ($useCaseCreate)($teamCreateRequest);

        $teamListRequest = new RequestList();
        $response = ($useCaseList)($teamListRequest);

        $this->assertNotEmpty($response->getPlayers());
        $this->assertInstanceOf(Player::class, $response->getPlayers()[0]);
    }
}

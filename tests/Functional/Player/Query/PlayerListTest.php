<?php

namespace App\Tests\Functional\Player\Query;

use App\Domain\Entity\Player;
use App\UseCase\Player\Query\GetPlayers\Request as RequestList;
use App\UseCase\Player\Query\GetPlayers\Response as ResponseList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlayerListTest extends KernelTestCase
{
    public function testListNotEmpty(): void
    {
        $kernel = self::bootKernel();

        $useCaseList = $kernel->getContainer()->get('usecase.player.list');

        $teamListRequest = new RequestList();
        $response = ($useCaseList)($teamListRequest);

        $this->assertNotEmpty($response->getPlayers());
        $this->assertInstanceOf(Player::class, $response->getPlayers()[0]);
    }

    public function testPlayersListShouldBeEmpty(): void
    {
        $kernel = self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $cn = $em->getConnection();
        $cn->executeQuery('DELETE FROM player');

        $useCase = $kernel->getContainer()->get('usecase.player.list');

        $playerListRequest = new RequestList();

        $response = ($useCase)($playerListRequest);

        $this->assertEmpty($response->getPlayers());
        $this->assertInstanceOf(ResponseList::class, $response);
    }

    public function testGetPlayer(): void
    {
        $kernel = self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $cn = $em->getConnection();
        $cn->executeQuery('DELETE FROM player');

        $useCase = $kernel->getContainer()->get('usecase.player.list');

        $playerListRequest = new RequestList();

        $response = ($useCase)($playerListRequest);

        $this->assertEmpty($response->getPlayers());
        $this->assertInstanceOf(ResponseList::class, $response);
    }
}

<?php

namespace App\Tests\Functional\Game\Query;

use App\Domain\Entity\Game;
use App\Infrastructure\Doctrine\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameListTest extends KernelTestCase
{
    public function testGetGameById(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        /** @var GameRepository $em */
        $gameRepository = static::$kernel->getContainer()->get('repository.game');

        $this->assertInstanceOf(Game::class, $gameRepository->get('0bdf9865-ce79-4464-9a92-19d81d1f3bf3'));
    }
}

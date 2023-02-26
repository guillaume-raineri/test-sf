<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\Game;
use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Domain\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class TeamTest extends TestCase
{
    public function testConstructor(): void
    {
        $id = Uuid::v4();
        $name = 'Team Name';
        $team = new Team($id, $name);

        $this->assertSame($id, $team->getId());
        $this->assertSame($name, $team->getName());
        $this->assertInstanceOf(Collection::class, $team->getPlayers());
        $this->assertEmpty($team->getPlayers());
        $this->assertInstanceOf(Collection::class, $team->getHomeGames());
        $this->assertEmpty($team->getHomeGames());
        $this->assertInstanceOf(Collection::class, $team->getAwayGames());
        $this->assertEmpty($team->getAwayGames());
    }

    public function testConstructThrowsValidationExceptionIfNameIsTooLong()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name must have less than 255 characters.');
        $id = Uuid::v4();
        $name = str_repeat('a', 256);
        new Team($id, $name);
    }

    public function testGetId(): void
    {
        $teamId = Uuid::v4();
        $team = new Team($teamId, 'Test Team');
        $this->assertSame($teamId, $team->getId());
    }

    public function testGetName(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $this->assertSame('Test Team', $team->getName());
    }

    public function testGetPlayers(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $player1 = new Player(Uuid::v4(), 'Player 1');
        $player2 = new Player(Uuid::v4(), 'Player 2');
        $team->addPlayer($player1);
        $team->addPlayer($player2);
        $this->assertCount(2, $team->getPlayers());
        $this->assertTrue($team->getPlayers()->contains($player1));
        $this->assertTrue($team->getPlayers()->contains($player2));
    }

    public function testAddPlayer(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $player = new Player(Uuid::v4(), 'Player');
        $team->addPlayer($player);
        $this->assertSame($team, $player->getTeam());
        $this->assertTrue($team->getPlayers()->contains($player));
    }

    public function testRemovePlayer(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $player = new Player(Uuid::v4(), 'Player');
        $team->addPlayer($player);
        $team->removePlayer($player);
        $this->assertNull($player->getTeam());
        $this->assertFalse($team->getPlayers()->contains($player));
    }

    public function testGetHomeGames(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $this->assertCount(0, $team->getHomeGames());
    }

    public function testSetHomeGames(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $game1 = $this->createMock(Game::class);
        $game2 = $this->createMock(Game::class);
        $team->setHomeGames(new ArrayCollection([$game1, $game2]));
        $this->assertCount(2, $team->getHomeGames());
        $this->assertTrue($team->getHomeGames()->contains($game1));
        $this->assertTrue($team->getHomeGames()->contains($game2));
    }

    public function testGetAwayGames(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $this->assertCount(0, $team->getAwayGames());
    }

    public function testSetAwayGames(): void
    {
        $team = new Team(Uuid::v4(), 'Test Team');
        $game1 = $this->createMock(Game::class);
        $game2 = $this->createMock(Game::class);
        $team->setAwayGames(new ArrayCollection([$game1, $game2]));
        $this->assertCount(2, $team->getAwayGames());
        $this->assertTrue($team->getAwayGames()->contains($game1));
        $this->assertTrue($team->getAwayGames()->contains($game2));
    }
}

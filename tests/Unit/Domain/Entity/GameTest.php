<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\Game;
use App\Domain\Entity\Team;
use App\Domain\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class GameTest extends TestCase
{
    public function testConstruct(): void
    {
        $id = Uuid::v4();
        $name = 'Test Game';
        $homeTeam = new Team(Uuid::v4(), 'test');
        $awayTeam = new Team(Uuid::v4(), 'test');

        $game = new Game($id, $name, $homeTeam, $awayTeam);

        $this->assertSame($id, $game->getId());
        $this->assertSame($name, $game->getName());
        $this->assertSame($homeTeam, $game->getHomeTeam());
        $this->assertSame($awayTeam, $game->getAwayTeam());
    }

    public function testConstructThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        $id = Uuid::v4();
        $name = str_repeat('a', 256);
        $homeTeam = new Team(Uuid::v4(), 'test');
        $awayTeam = new Team(Uuid::v4(), 'test');

        new Game($id, $name, $homeTeam, $awayTeam);
    }

    public function testSetName(): void
    {
        $id = Uuid::v4();
        $name = 'Test Game';
        $homeTeam = new Team(Uuid::v4(), 'test');
        $awayTeam = new Team(Uuid::v4(), 'test');

        $game = new Game($id, $name, $homeTeam, $awayTeam);

        $newName = 'New Test Game';
        $game->setName($newName);

        $this->assertSame($newName, $game->getName());
    }

    public function testSetHomeTeam(): void
    {
        $id = Uuid::v4();
        $name = 'Test Game';
        $homeTeam = new Team(Uuid::v4(), 'test');
        $awayTeam = new Team(Uuid::v4(), 'test');

        $game = new Game($id, $name, $homeTeam, $awayTeam);

        $newHomeTeam = new Team(Uuid::v4(), 'new home team');
        $game->setHomeTeam($newHomeTeam);

        $this->assertSame($newHomeTeam, $game->getHomeTeam());
    }

    public function testSetAwayTeam(): void
    {
        $id = Uuid::v4();
        $name = 'Test Game';
        $homeTeam = new Team(Uuid::v4(), 'test');
        $awayTeam = new Team(Uuid::v4(), 'test');

        $game = new Game($id, $name, $homeTeam, $awayTeam);

        $newAwayTeam = new Team(Uuid::v4(), 'New Away Team');
        $game->setAwayTeam($newAwayTeam);

        $this->assertSame($newAwayTeam, $game->getAwayTeam());
    }
}

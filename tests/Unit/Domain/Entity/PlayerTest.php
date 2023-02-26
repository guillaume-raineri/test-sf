<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Domain\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class PlayerTest extends TestCase
{
    public function testConstructor(): void
    {
        $id = Uuid::v4();
        $name = 'John Doe';
        $player = new Player($id, $name);

        $this->assertSame($id, $player->getId());
        $this->assertSame($name, $player->getName());
        $this->assertNull($player->getTeam());
    }

    public function testSetName(): void
    {
        $id = Uuid::v4();
        $name = 'John Doe';
        $player = new Player($id, $name);

        $newName = 'Jane Smith';
        $player->setName($newName);

        $this->assertSame($newName, $player->getName());
    }

    public function testSetNameThrowsValidationException(): void
    {
        $player = new Player(Uuid::v4(), 'a');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name must have less than 255 characters.');

        $player->setName(str_repeat('a', 256));
    }

    public function testSetTeam(): void
    {
        $id = Uuid::v4();
        $name = 'John Doe';
        $player = new Player($id, $name);

        $team = new Team(Uuid::v4(), 'ACME Team');
        $player->setTeam($team);

        $this->assertSame($team, $player->getTeam());
    }

    public function testSetTeamToNull(): void
    {
        $id = Uuid::v4();
        $name = 'John Doe';
        $team = new Team(Uuid::v4(), 'ACME Team');
        $player = (new Player($id, $name))->setTeam($team);

        $player->setTeam(null);

        $this->assertNull($player->getTeam());
    }
}

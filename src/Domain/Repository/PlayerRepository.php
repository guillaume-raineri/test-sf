<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Player;

interface PlayerRepository
{
    public function save(Player $player): void;

    public function get(string $id): Player|null;

    public function create(Player $player): void;

    /**
     * @return array<Player>
     */
    public function findAll(): array;
}

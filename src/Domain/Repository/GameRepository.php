<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Game;

interface GameRepository
{
    public function create(Game $game): void;

    /**
     * @return array<Game>
     */
    public function findAll(): array;
}

<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Team;

interface TeamRepository
{
    public function create(Team $team): void;

    /**
     * @return array<Team>
     */
    public function findAll(): array;

    /**
     * @return bool
     */
    public function exists(string $name): bool;
}

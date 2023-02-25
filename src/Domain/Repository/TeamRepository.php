<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Team;

interface TeamRepository
{
    public function get(string $id): Team|null;

    public function save(Team $team): void;

    public function create(Team $team): void;

    /**
     * @return array<Team>
     */
    public function findAll(): array;

    public function exists(string $name): bool;
}

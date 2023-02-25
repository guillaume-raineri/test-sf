<?php

namespace App\UseCase\Team\Query\GetTeams;

use App\Domain\Entity\Team;

final class Response
{
    public function __construct(private readonly array $teams)
    {
    }

    /**
     * @return array<Team>
     */
    public function getPlayers(): array
    {
        return $this->teams;
    }
}

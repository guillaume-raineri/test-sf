<?php

namespace App\UseCase\Game\Command\CreateGame;

final class Request
{
    public function __construct(
        private readonly string $name,
        private readonly string $homeTeamId,
        private readonly string $awayTeamId
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHomeTeamId(): string
    {
        return $this->homeTeamId;
    }

    public function getAwayTeamId(): string
    {
        return $this->awayTeamId;
    }
}

<?php

namespace App\UseCase\Game\Query\GetGames;

use App\Domain\Entity\Game;

final class Response
{
    public function __construct(private readonly array $games)
    {
    }

    /**
     * @return array<Game>
     */
    public function getGames(): array
    {
        return $this->games;
    }
}

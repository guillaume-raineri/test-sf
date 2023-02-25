<?php

namespace App\UseCase\Player\Query\GetPlayers;

use App\Domain\Entity\Player;

final class Response
{
    public function __construct(private readonly array $players)
    {
    }

    /**
     * @return array<Player>
     */
    public function getPlayers(): array
    {
        return $this->players;
    }
}

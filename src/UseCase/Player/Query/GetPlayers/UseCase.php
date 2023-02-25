<?php

namespace App\UseCase\Player\Query\GetPlayers;

use App\Domain\Repository\PlayerRepository;

final class UseCase
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->playerRepository->findAll());
    }
}

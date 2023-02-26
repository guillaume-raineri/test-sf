<?php

namespace App\UseCase\Game\Query\GetGames;

use App\Domain\Repository\GameRepository;

class UseCase
{
    public function __construct(
        private readonly GameRepository $gameRepository
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->gameRepository->findAll());
    }
}

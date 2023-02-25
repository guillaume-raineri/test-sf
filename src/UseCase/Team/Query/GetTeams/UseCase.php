<?php

namespace App\UseCase\Team\Query\GetTeams;

use App\Domain\Repository\TeamRepository;

final class UseCase
{
    public function __construct(private readonly TeamRepository $teamRepository)
    {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->teamRepository->findAll());
    }
}

<?php

namespace App\UseCase\Team\Query\GetTeamGames;

use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\Repository\TeamRepository;
use Symfony\Component\Uid\Uuid;
use Throwable;

final class UseCase
{
    public function __construct(private readonly TeamRepository $teamRepository)
    {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $teamId = Uuid::fromString($request->getTeamId());
        } catch (Throwable $e) {
            throw new ValidationException('Bad request.');
        }

        $team = $this->teamRepository->get($teamId);
        if (null === $team) {
            throw new NotFoundException('Team not found.');
        }

        return new Response($team->getGames());
    }
}

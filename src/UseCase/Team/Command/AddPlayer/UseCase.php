<?php

namespace App\UseCase\Team\Command\AddPlayer;

use App\Domain\Exception\ValidationException;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\TeamRepository;
use Symfony\Component\Uid\Uuid;
use Throwable;

class UseCase
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        try {
            $teamId = Uuid::fromString($request->getTeamId());
            $playerId = Uuid::fromString($request->getPlayerId());
        } catch (Throwable $e) {
            throw new ValidationException('Bad request.');
        }

        $team = $this->teamRepository->get($teamId);
        if (null === $team) {
            throw new ValidationException('Team not found.');
        }

        $player = $this->playerRepository->get($playerId);
        if (null === $player) {
            throw new ValidationException('Player not found.');
        }

        $team->addPlayer($player);

        $this->teamRepository->save($team);

        return new Response();
    }
}

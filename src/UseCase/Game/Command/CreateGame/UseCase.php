<?php

namespace App\UseCase\Game\Command\CreateGame;

use App\Domain\Entity\Game;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\Repository\GameRepository;
use App\Domain\Repository\TeamRepository;
use Symfony\Component\Uid\Uuid;
use Throwable;

class UseCase
{
    public function __construct(
        private readonly GameRepository $gameRepository,
        private readonly TeamRepository $teamRepository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        if ($request->getHomeTeamId() === $request->getAwayTeamId()) {
            throw new ValidationException('Can\'t use same team');
        }

        try {
            $homeTeamId = Uuid::fromString($request->getHomeTeamId());
            $awayTeamId = Uuid::fromString($request->getAwayTeamId());
        } catch (Throwable $e) {
            throw new ValidationException('Bad request.');
        }

        $homeTeam = $this->teamRepository->get($homeTeamId);
        if (null === $homeTeam) {
            throw new NotFoundException('Home team not found.');
        }

        $awayTeam = $this->teamRepository->get($awayTeamId);
        if (null === $awayTeam) {
            throw new NotFoundException('Away team not found.');
        }

        $game = (new Game(Uuid::v4(), $request->getName(), $homeTeam, $awayTeam));

        $this->gameRepository->create($game);

        return new Response($game->getId());
    }
}

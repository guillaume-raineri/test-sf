<?php

namespace App\UseCase\Team\CreateTeam;

use App\Domain\Entity\Team;
use App\Domain\Exception\ValidationException;
use App\Domain\Repository\TeamRepository;
use Symfony\Component\Uid\Uuid;

class UseCase
{
    public function __construct(
        private readonly TeamRepository $teamRepository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $team = (new Team(Uuid::v4(), $request->getName()));

        if ($this->teamRepository->exists($team->getName())) {
            throw new ValidationException('This team name is already used');
        }

        $this->teamRepository->create($team);

        return new Response($team->getId());
    }
}

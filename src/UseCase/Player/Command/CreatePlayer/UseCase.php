<?php

namespace App\UseCase\Player\Command\CreatePlayer;

use App\Domain\Entity\Player;
use App\Domain\Exception\ValidationException;
use App\Domain\Repository\PlayerRepository;
use Symfony\Component\Uid\Uuid;

class UseCase
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $this->playerRepository->create($player = (new Player(Uuid::v4(), $request->getName())));

        return new Response($player->getId());
    }
}

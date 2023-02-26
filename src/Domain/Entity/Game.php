<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class Game
{
    private Uuid $id;

    private string $name;

    private Team $homeTeam;
    private Team $awayTeam;

    /**
     * @throws ValidationException
     */
    public function __construct(
        Uuid $id,
        string $name,
        Team $homeTeam,
        Team $awayTeam
    ) {
        $this->id = $id;
        if (mb_strlen($name) > 255) {
            throw new ValidationException('Name must have less than 255 characters.');
        }
        $this->name = $name;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }
}

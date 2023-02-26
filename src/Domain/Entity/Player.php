<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class Player
{
    private Uuid $id;

    private string $name;

    private Team|null $team = null;

    /**
     * @throws ValidationException
     */
    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        $this->setName($name);
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
        if (mb_strlen($name) > 255) {
            throw new ValidationException('Name must have less than 255 characters.');
        }

        $this->name = $name;

        return $this;
    }

    public function getTeam(): Team|null
    {
        return $this->team;
    }

    public function setTeam(Team|null $team): self
    {
        $this->team = $team;

        return $this;
    }
}

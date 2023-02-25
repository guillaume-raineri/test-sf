<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Team
{
    private Uuid $id;

    private string $name;

    private Collection $players;

    /**
     * @throws ValidationException
     */
    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        if (mb_strlen($name) > 255) {
            throw new ValidationException('Name must have less than 255 characters.');
        }
        $this->name = $name;
        $this->players = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }
}

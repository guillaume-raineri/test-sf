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
    private Collection $homeGames;
    private Collection $awayGames;

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
        $this->homeGames = new ArrayCollection();
        $this->awayGames = new ArrayCollection();
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

    public function getHomeGames(): Collection
    {
        return $this->homeGames;
    }

    public function setHomeGames(Collection $homeGames): self
    {
        $this->homeGames = $homeGames;

        return $this;
    }

    public function getAwayGames(): Collection
    {
        return $this->awayGames;
    }

    public function setAwayGames(Collection $awayGames): self
    {
        $this->awayGames = $awayGames;

        return $this;
    }

    /**
     * @return Game[]
     */
    public function getGames(): array
    {
        return array_merge($this->homeGames->toArray(), $this->awayGames->toArray());
    }
}

<?php

namespace App\UseCase\Team\Command\CreateTeam;

final class Request
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}

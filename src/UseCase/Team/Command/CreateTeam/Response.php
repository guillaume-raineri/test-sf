<?php

namespace App\UseCase\Team\Command\CreateTeam;

use Symfony\Component\Uid\Uuid;

class Response
{
    public function __construct(private readonly Uuid $id)
    {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}

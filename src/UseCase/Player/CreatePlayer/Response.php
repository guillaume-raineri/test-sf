<?php

namespace App\UseCase\Player\CreatePlayer;

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

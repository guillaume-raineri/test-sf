<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class Team
{
    private Uuid $id;

    private string $name;

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
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

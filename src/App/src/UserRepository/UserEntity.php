<?php

declare(strict_types=1);

namespace App\UserRepository;

use Axleus\Db;

final class UserEntity implements Db\EntityInterface
{
    use Db\EntityTrait;

    public function __construct(
        private ?int $id = null,
        private ?string $userName = null,
        #[\SensitiveParameter]
        private ?string $hash = null
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }
}

<?php

namespace SAE_PHP\models;

class User
{
    public function __construct(
        private ?int $id,
        private string $username,
        private string $email,
        private ?string $passwordHash = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }
}

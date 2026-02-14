<?php

namespace App\Services\AuthService\DTO\Data;

final readonly class LoginDto
{
    public function __construct(
        private string $email,
        private string $password,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

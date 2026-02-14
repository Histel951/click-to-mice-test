<?php

namespace App\Services\AuthService\DTO\Results;

use JsonSerializable;

final readonly class LoginResultDto implements JsonSerializable
{
    public function __construct(
        private string $accessToken,
    ) {}

    public function getToken(): string
    {
        return $this->accessToken;
    }


    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
        ];
    }
}

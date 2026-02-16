<?php

namespace App\Services\AuthService;

use App\Models\User;
use App\Services\AuthService\DTO\Results\LoginResultDto;
use App\Services\AuthService\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;

final class AuthService
{
    /**
     * Авторизирует пользователя и возвращает токен
     *
     * @param string $email
     * @param string $password
     * @return LoginResultDto
     */
    public function login(string $email, string $password): LoginResultDto
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException("Invalid credentials");
        }

        return new LoginResultDto($user->createToken('api-token')->plainTextToken);
    }

    /**
     * Логаут
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}

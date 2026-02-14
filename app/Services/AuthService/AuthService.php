<?php

namespace App\Services\AuthService;

use App\Models\User;
use App\Services\AuthService\DTO\Data\LoginDto;
use App\Services\AuthService\DTO\Results\LoginResultDto;
use App\Services\AuthService\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;

final class AuthService
{
    /**
     * Авторизирует пользователя и возвращает токен
     *
     * @param LoginDto $loginDto
     * @return LoginResultDto
     */
    public function login(LoginDto $loginDto): LoginResultDto
    {
        $user = User::where('email', $loginDto->getEmail())->first();

        if (!$user || !Hash::check($loginDto->getPassword(), $user->password)) {
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

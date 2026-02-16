<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $authService): ApiResponse
    {
        $result = $authService->login(
            $request->getEmail(),
            $request->getPassword()
        );

        return new ApiResponse($result, 'Вы авторизовались');
    }

    public function logout(Request $request, AuthService $authService): ApiResponse
    {
        $authService->logout($request->user());

        return new ApiResponse([], 'Вы вышли');
    }
}

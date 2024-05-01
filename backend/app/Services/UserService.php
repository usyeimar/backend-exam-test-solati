<?php

namespace App\Services;

use App\DataTransferObjects\MapperResponseDto;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function me()
    {
        return auth()->user();
    }

    public function update(array $data)
    {
        return auth()->user()->update($data);
    }

    public function create(array $data): MapperResponseDto
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        $token = $user->createToken('auth_token');

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => [
                'user' => UserResource::make($user)->resolve(),
                'token' => [
                    'value' => $token->accessToken,
                    'type' => 'Bearer',
                    'expires_at' => $token->token->expires_at->toDateTimeString(),
                    'expires_in' => $token->token->expires_at->getTimestamp(),
                ],
            ],
        ]);

    }
}

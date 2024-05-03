<?php

namespace App\Services;

use App\DataTransferObjects\MapperResponseDto;
use App\Http\Resources\CheckNullOrEmptyValues;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthenticationService
{

    /**
     * Genera un token de acceso para el usuario.
     * @throws \Exception
     */
    public function login(array $data, bool $withGrantPassword = false): JsonResponse
    {
        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if ($withGrantPassword) {
            $token = $this->proxy(
                $data + [
                    'grantType' => 'password',
                ]
            );

        } else {
            $isLogged = auth()->attempt($data);

            if (!$isLogged) {
                throw new Exception(
                    message: 'The provided credentials do not match our records.',
                );
            }


            $token = $user->createToken('auth_token');

            $token = [
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->token->expires_at->toDateTimeString(),
                'expires_in' => $token->token->expires_at->getTimestamp(),
            ];
        }


        return response()->json(CheckNullOrEmptyValues::check([
            'success' => true,
            'message' => 'User authenticated successfully.',
            'data' => [
                ...UserResource::make($user)->resolve(),
                'token' => [
                    'value' => $token['access_token'],
                    'refresh_value' => $token['refresh_token'] ?? null,
                    'expires_at' => $token['expires_at'],
                    'expires_in' => $token['expires_in'],
                    'type' => $token['token_type'],
                ],

            ],
        ]));
    }

    /**
     * Registrar usuario.
     * @param array $data
     * @return MapperResponseDto
     */
    public function register(array $data): MapperResponseDto
    {

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Email verification link sent to your email.',
        ]);

    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param array $data the data to send to the server
     *
     * @throws \Exception
     */
    private function proxy(array $data): array
    {
        $url = route('v1.passport.token');


        /** @var Response */
        $response = app(Kernel::class)->handle(Request::create($url, 'POST', [
            'grant_type' => $data['grantType'],
            'client_id' => config('passport.password_grant_client.id'),
            'client_secret' => config('passport.password_grant_client.secret'),
            'username' => $data['email'],
            'password' => $data['password'],
            'scope' => '',
        ]));


        $data = json_decode($response->content());


        if ($response->status() !== 200) {
            throw new Exception(
                message: 'There was an error authenticating the user with the provided credentials (' . $data->message . ').',
            );
        }

        return [
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'token_type' => $data->token_type,
            'expires_in' => $data->expires_in,
            'expires_at' => now()->addSeconds($data->expires_in)->toDateTimeString(),
        ];
    }

}

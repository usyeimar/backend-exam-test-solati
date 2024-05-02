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
use Laravel\Passport\PersonalAccessTokenResult;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationService
{

    /**
     * Genera un token de acceso para el usuario.
     * @throws \Exception
     */
    public function login(array $data): JsonResponse
    {
        $token = auth()->attempt($data);

        if (!$token) {
            throw new Exception(
                message: 'The provided credentials do not match our records.',
            );
        }

        $user = User::query()->where('email', $data['email'])
            ->first();

        //validate if email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json(CheckNullOrEmptyValues::check([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Email not verified',
                        'detail' => 'Please verify your email address.',
                    ],
                ]
            ]), 401);
        }

        $token = $this->createNewToken($user);

        return response()->json(CheckNullOrEmptyValues::check([
            'success' => true,
            'message' => 'User authenticated successfully.',
            'data' => [
                'id' => $user->id,
                'object' => 'user',
                'name' => $user->name,
                'timezone' => $user->timezone,
                'locale' => $user->locale,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'token' => [
                    'value' => $token->accessToken,
                    'expires_at' => now()->addSeconds($token->token->expires_at->getTimestamp())->toDateTimeString(),
                    'expires_in' => now()->addSeconds($token->token->expires_at->getTimestamp())->getTimestamp(),
                    'type' => 'Bearer',
                ],

            ],
        ]));
    }

    private function createNewToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('auth_token');
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
                message: 'Hubo un error al autenticar al usuario con las credenciales proporcionadas (' . $data->message . ').',
            );
        }

        return [
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'token_type' => $data->token_type,
            'expires_in' => $data->expires_in,
        ];
    }

}

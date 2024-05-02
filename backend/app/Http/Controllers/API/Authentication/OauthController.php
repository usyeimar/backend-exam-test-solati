<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckNullOrEmptyValues;
use Exception;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class OauthController extends Controller
{
    /**
     * @throws \Exception
     */
    public function __invoke(
        \Illuminate\Http\Request $request
    )
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        [
                            'title' => 'Credenciales inválidas',
                            'detail' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
                        ],
                    ],
                ], 401);
            }

            $user = auth()->user();


            $data = $this->proxy(
                $credentials + [
                    'grantType' => 'password',
                ]
            );




            return response()->json(CheckNullOrEmptyValues::check([
                'success' => true,
                'message' => 'Usuario autenticado exitosamente.',
                'data' => [
                    'id' => $user->id,
                    'object' => 'user',
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'token' => [
                        'value' => $data['access_token'],
                        'refresh_value' => $data['refresh_token'],
                        'expires_at' => now()->addSeconds($data['expires_in'])->toDateTimeString(),
                        'expires_in' => now()->addSeconds($data['expires_in'])->getTimestamp(),
                        'type' => $data['token_type'],
                    ],

                ],
            ]));
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Autenticación fallida',
                        'detail' => $e->getMessage(),
                    ],
                ],
            ], 401);
        }
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

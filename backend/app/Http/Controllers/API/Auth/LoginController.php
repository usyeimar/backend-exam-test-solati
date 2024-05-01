<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\CheckNullOrEmptyValues;
use App\Models\User;
use Exception;
use Laravel\Passport\PersonalAccessTokenResult;

class LoginController extends Controller
{
    /**
     * Genera un token de acceso para el usuario.
     *
     * @throws Exception
     */
    public function __invoke(LoginRequest $request)
    {

        try {
            $token = auth()->attempt($request->only('email', 'password'));

            if (! $token) {
                abort(401, 'Las credenciales proporcionadas no coinciden con nuestros registros.');
            }

            $user = User::where('email', $request->email)
                ->first();

            $token = $this->createNewToken($user);

            return response()->json(CheckNullOrEmptyValues::check([
                'success' => true,
                'message' => 'Usuario autenticado exitosamente.',
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
        } catch (Exception $e) {
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

    private function createNewToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('auth_token');
    }
}

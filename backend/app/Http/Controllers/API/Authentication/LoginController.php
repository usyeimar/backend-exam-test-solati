<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\CheckNullOrEmptyValues;
use App\Models\User;
use App\Services\AuthenticationService;
use Exception;
use Illuminate\Database\QueryException;
use Laravel\Passport\PersonalAccessTokenResult;

class LoginController extends Controller
{
    /**
     * Genera un token de acceso para el usuario.
     *
     * @throws Exception
     */
    public function __invoke(LoginRequest $request, AuthenticationService $service)
    {

        try {
            return $service->login($request->validated(), true);
        } catch (Exception $e) {
            if ($e instanceof QueryException) {
                throw $e;
            }

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


}

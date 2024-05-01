<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class RegisterUserController extends Controller
{
    /**
     * Registrar usuario.
     *
     * @throws ValidationException
     */
    public function __invoke(CreateUserRequest $request, UserService $service): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => $user->message(),
                'data' => $user->data(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Algo salió mal',
                        'detail' => $e->getMessage(),
                    ],
                ],
                'debug' => App::environment('production') ? null : [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]
            ], 500);
        }
    }
}

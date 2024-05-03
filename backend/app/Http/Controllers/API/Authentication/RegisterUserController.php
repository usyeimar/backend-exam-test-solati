<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class RegisterUserController extends Controller
{
    /**
     * Registrar usuario.
     *
     * @throws ValidationException
     */
    public function __invoke(CreateUserRequest $request, AuthenticationService $service): JsonResponse
    {
        try {
            $user = $service->register($request->validated());
            return response()->json([
                'success' => true,
                'message' => $user->message(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Algo salió mal',
                        'detail' => $e->getMessage(),
                    ],
                ]
            ], 500);
        }
    }
}

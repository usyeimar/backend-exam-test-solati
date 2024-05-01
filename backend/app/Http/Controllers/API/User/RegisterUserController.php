<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Services\UserService;
use Exception;

class RegisterUserController extends Controller
{
    /**
     * Registrar usuario.
     */
    public function __invoke(CreateUserRequest $request, UserService $service)
    {
        try {
            return $service->create($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Algo salió mal',
                        'detail' => $e->getMessage(),
                    ],
                ],
            ], 500);
        }
    }
}

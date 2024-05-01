<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Obtener usuario autenticado.
     *
     * @group Usuario
     *
     * Este endpoint permite obtener la información del usuario autenticado.
     */
    public function __invoke(Request $request)
    {
        try {
            return new UserResource(auth()->user());
        } catch (\Exception $e) {
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

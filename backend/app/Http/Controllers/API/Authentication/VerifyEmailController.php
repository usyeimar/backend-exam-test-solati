<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Verificar la dirección de correo electrónico del usuario.
     *
     * A través de esta API, se verifica la dirección de correo electrónico del usuario.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
//            if ($request->wantsJson()) {
//                return response()->json([
//                    'success' => true,
//                    'message' => 'User email already verified.'
//                ]);
//            }

            return redirect()->intended(
                config('app.frontend_url') . '/dashboard?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // if block added just to test on postman.
//        if ($request->wantsJson()) {
//            return response()->json([
//                'success' => true,
//                'message' => 'User email verified successfully.'
//            ]);
//        }

        return redirect()->intended(
            config('app.frontend_url') . '/dashboard?verified=1'
        );
    }
}

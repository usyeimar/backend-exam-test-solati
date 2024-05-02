<?php

namespace App\Http\Controllers\API\Authentication;

use App\Helpers\PassportRevokerHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Revoke the user's current token.
     */
    public function __invoke(Request $request)
    {
        PassportRevokerHelper::create(user: Auth::user())->deleteCurrentToken();

        return response()->noContent();
    }
}

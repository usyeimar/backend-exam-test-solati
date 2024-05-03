<?php

namespace App\Http\Controllers\API\Attachments;

use App\Http\Controllers\Controller;
use App\Services\AttachmentService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AttachmentService $service)
    {
        try {
            $attachments = $service->index();

            return response()->json([
                'success' => $attachments->success(),
                'message' => $attachments->message(),
                'data' => $attachments->data(),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Something went wrong',
                        'detail' => $th->getMessage(),
                    ],
                ],
            ], 400);
        }
    }
}

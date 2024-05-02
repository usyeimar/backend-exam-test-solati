<?php

namespace App\Http\Controllers\API\Attachments;

use App\Http\Controllers\Controller;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Throwable;

class UploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, AttachmentService $service)
    {
        try {
            $attachment = $service->upload($request->file('file'));

            return response()->json([
                'success' => $attachment->success(),
                'message' => $attachment->message(),
                'data' => $attachment->data(),
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Something went wrong',
                        'detail' => $e->getMessage(),
                    ],
                ],
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API\Attachments;

use App\Http\Controllers\Controller;
use App\Services\AttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $attachment_uuid, AttachmentService $service): BinaryFileResponse | JsonResponse
    {
        try {
            return $service->download($attachment_uuid);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Something went wrong',
                        'detail' => $e->getMessage(),
                    ],
                ],
            ], 400);
        }
    }
}

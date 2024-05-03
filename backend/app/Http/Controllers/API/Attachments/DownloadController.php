<?php

namespace App\Http\Controllers\API\Attachments;

use App\Helpers\HttpHelper;
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
    public function __invoke(string $attachment_uuid, AttachmentService $service): \Illuminate\Http\Response|JsonResponse|BinaryFileResponse
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
            ], HttpHelper::checkCode($e->getCode()) ? $e->getCode() : 400);
        }
    }
}

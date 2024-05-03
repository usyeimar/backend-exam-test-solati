<?php

namespace App\Http\Controllers\API\Attachments;

use App\Helpers\HttpHelper;
use App\Http\Controllers\Controller;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Throwable;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $attachment_uuid, AttachmentService $service)
    {
        try {

            $attachment = $service->delete($attachment_uuid);

            return response()->json([
                'success' => $attachment->success(),
                'message' => $attachment->message(),
            ]);
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

<?php

namespace App\Http\Controllers\API\Attachments;

use App\Helpers\HttpHelper;
use App\Http\Controllers\Controller;
use App\Services\AttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UploadController extends Controller
{
    /**
     * Cargar  un archivo.
     *
     * Este servicio permite cargar un archivo al servidor.
     * @param Request $request
     * @param AttachmentService $service
     * @return JsonResponse
     */
    public function __invoke(Request $request, AttachmentService $service)
    {
        try {
            if (!$request->hasFile('attachment')) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        [
                            'title' => 'File not found',
                            'detail' => 'Please provide a file to upload.',
                        ],
                    ],
                ], 422);
            }

            $attachment = $service->upload($request->file('attachment'));

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
            ], HttpHelper::checkCode($e->getCode()) ? $e->getCode() : 400);
        }
    }
}

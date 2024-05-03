<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'object' => 'attachment',
            'url' => $this->url(),
            'type' => $this->type(),
            'display_name' => $this->display_name,
            'hash_name' => $this->hash_name,
            'path' => $this->path,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
        ];
    }
}

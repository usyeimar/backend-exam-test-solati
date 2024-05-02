<?php

namespace App\Http\Resources;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return CheckNullOrEmptyValues::check([
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => 'task',
            'title' => $this->title,
            'description' => $this->description,
            'completed' => $this->completed,
            'user' => new UserResource($this->user),
            'attachments' => AttachmentResource::collection($this->attachments),
            'due_at' => DateHelper::getShortDate($this->due_at),
            'completed_at' => DateHelper::getTimestamp($this->completed_at),
            'created_at' => DateHelper::getTimestamp($this->created_at),
            'updated_at' => DateHelper::getTimestamp($this->updated_at),
        ]);
    }
}

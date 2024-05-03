<?php

namespace App\Http\Resources;

use App\Helpers\DateHelper;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public User $this;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid, // 'uuid' is a custom attribute
            'object' => 'user', //
            'name' => $this->name,
            'email' => $this->email,
            'confirmed' => $this->email_verified_at !== null,
            'email_verified_at' => $this->email_verified_at ? DateHelper::getTimestamp($this->email_verified_at) : null, // DateHelper::getTimestamp() is a custom helper function
        ];
    }
}

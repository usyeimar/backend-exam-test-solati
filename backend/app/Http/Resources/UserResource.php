<?php

namespace App\Http\Resources;

use App\Helpers\DateHelper;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public User $this;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'user', // 'type' => 'user' is a JSON:API convention
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => DateHelper::getTimestamp($this->email_verified_at), // DateHelper::getTimestamp() is a custom helper function
            'created_at' => DateHelper::getTimestamp($this->created_at), // DateHelper::getTimestamp() is a custom helper function
            'updated_at' => DateHelper::getTimestamp($this->updated_at), // DateHelper::getTimestamp() is a custom helper function
            'links' => [
                'self' => route('v1.user.me'),
            ],
        ];
    }
}

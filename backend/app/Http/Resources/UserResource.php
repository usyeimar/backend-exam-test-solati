<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'user', // 'type' => 'user' is a JSON:API convention
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

}

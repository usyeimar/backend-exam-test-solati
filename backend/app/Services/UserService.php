<?php

namespace App\Services;

use App\DataTransferObjects\MapperResponseDto;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function me()
    {
        return auth()->user();
    }

    public function update(array $data)
    {
        return auth()->user()->update($data);
    }

}

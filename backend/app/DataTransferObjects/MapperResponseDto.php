<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

readonly class MapperResponseDto
{
    public function __construct(
        private bool $success,
        private string $message,
        private array|AnonymousResourceCollection $data,
        private array $errors = []
    ) {
    }

    public function success(): bool
    {
        return $this->success;
    }

    public function data(): array|AnonymousResourceCollection
    {
        return $this->data;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function message(): string
    {
        return $this->message;
    }

    public static function create(array $data): self
    {
        return new self(
            success: $data['success'],
            message: $data['message'],
            data: $data['data'],
            errors: $data['errors'] ?? []
        );
    }

    public function toData(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ];
    }
}

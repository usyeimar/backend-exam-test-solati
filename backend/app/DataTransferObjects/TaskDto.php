<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

readonly class TaskDto
{


    public function __construct(
        private readonly ?string             $title,
        private readonly ?string             $description,
        private readonly ?\DateTimeImmutable $completed_at,
        private readonly ?\DateTimeImmutable $due_at,
        private readonly bool                $completed = false,
    )
    {
    }


    public function title(): ?string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function completedAt(): ?\DateTimeImmutable
    {
        return $this->completed_at;
    }

    public function dueAt(): ?\DateTimeImmutable
    {
        return $this->due_at;
    }

    public function completed(): bool
    {
        return $this->completed;
    }


    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            completed_at: Carbon::parse($data['completed_at'] ?? null)->toDateTimeImmutable() ?? null,
            due_at: Carbon::parse($data['due_at'] ?? null)->toDateTimeImmutable() ?? null,
            completed: $data['completed'] ?? false,
        );
    }

}

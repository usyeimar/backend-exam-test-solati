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
        private readonly ?string             $user_id = null,
    )
    {
    }

    /**
     * Título de la tarea
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * Descripción de la tarea
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Fecha de completado de la tarea
     * @return \DateTimeImmutable|null
     */
    public function completedAt(): ?\DateTimeImmutable
    {
        return $this->completed_at;
    }

    /**
     * Fecha de vencimiento de la tarea
     * @return \DateTimeImmutable|null
     */
    public function dueAt(): ?\DateTimeImmutable
    {
        return $this->due_at;
    }

    /**
     * Indica si la tarea está completada
     * @return bool
     */
    public function completed(): bool
    {
        return $this->completed;
    }

    /**
     * ID del usuario al que pertenece la tarea
     * @return string|null
     */
    public function userId(): ?string
    {
        return $this->user_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            completed_at: Carbon::parse($data['completed_at'] ?? null)->toDateTimeImmutable() ?? null,
            due_at: Carbon::parse($data['due_at'] ?? null)->toDateTimeImmutable() ?? null,
            completed: $data['completed'] ?? false,
            user_id: $data['user_id'] ?? null,
        );
    }
}

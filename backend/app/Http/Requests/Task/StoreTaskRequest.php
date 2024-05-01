<?php

namespace App\Http\Requests\Task;

use App\DataTransferObjects\TaskDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'due_at' => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required', // 'El título es requerido
            'title.string' => 'The title must be a string', // 'El título debe ser una cadena de texto
            'description.required' => 'The description is required', // 'La descripción es requerida
            'description.string' => 'The description must be a string', // 'La descripción debe ser una cadena de texto
            'due_at.required' => 'The due date is required', // 'La fecha de vencimiento es requerida
            'due_at.date' => 'The due date must be a date', // 'La fecha de vencimiento debe ser una fecha

        ];
    }

    /**
     * Get Task data transfer object
     */
    public function dto(): TaskDto
    {
        return TaskDto::fromArray($this->validated());
    }
}

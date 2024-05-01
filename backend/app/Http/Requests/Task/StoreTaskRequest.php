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
            'title.required' => 'El título es requerido',
            'title.string' => 'El título debe ser un texto',
            'description.required' => 'La descripción es requerida',
            'description.string' => 'La descripción debe ser un texto',
            'due_at.required' => 'La fecha de vencimiento es requerida',
            'due_at.date' => 'La fecha de vencimiento debe ser una fecha',
            'due_at.after' => 'La fecha de vencimiento debe ser después de hoy',
            'due_at.date_format' => 'La fecha de vencimiento debe tener el formato Y-m-d',
        ];
    }

    /**
     * Get Task data transfer object
     * @return TaskDto
     */
    public function dto(): TaskDto
    {
        return TaskDto::fromArray($this->validated());
    }
}

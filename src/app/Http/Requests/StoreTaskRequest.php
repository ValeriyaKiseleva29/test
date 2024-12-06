<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:urgent,non-urgent',
            'attachment' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'leader_id' => 'nullable|exists:users,id',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf,docx,txt|max:2048',

        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Название задачи обязательно для заполнения.',
            'name.max' => 'Название задачи не должно превышать 255 символов.',
            'start_date.required' => 'Укажите дату начала задачи.',
            'end_date.required' => 'Укажите дату завершения задачи.',
            'priority.required' => 'Выберите приоритет задачи.',
            'project_id.required' => 'Выберите проект для задачи.',
            'file.max' => 'Размер файла не должен превышать 2 МБ.',
        ];
    }
}

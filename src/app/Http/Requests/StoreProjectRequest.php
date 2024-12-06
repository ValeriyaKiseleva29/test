<?php

namespace App\Http\Requests;




use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'participants' => 'nullable|array',
            'leader' => 'nullable|integer|exists:users,id',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf,docx,txt|max:2048',

        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Введите название проекта.',
            'name.max' => 'Название проекта не должно превышать 255 символов.',
            'description.max' => 'Описание проекта не должно превышать 1000 символов.',
            'start_date.required' => 'Укажите дату начала проекта.',
            'end_date.required' => 'Укажите дату завершения проекта.',
            'end_date.after_or_equal' => 'Дата завершения проекта не может быть раньше даты начала.',

        ];
    }
}

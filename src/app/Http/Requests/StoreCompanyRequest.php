<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
//    public function store(StoreCompanyRequest $request)
//    {
//        // Если валидация проходит, этот код выполнится
//        $company = Company::create($request->validated());
//
//        auth()->login($company);
//        session(['company_id' => $company->id]);
//
//        return redirect()->route('dashboard.index');
//    }

    /**
     * Определить, авторизован ли пользователь для выполнения этого запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации, применяемые к запросу.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Пожалуйста, укажите название компании.',
            'email.required' => 'Введите адрес электронной почты.',
            'email.email' => 'Введите действительный адрес электронной почты.',
            'email.unique' => 'Этот адрес электронной почты уже зарегистрирован.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ];
    }

}

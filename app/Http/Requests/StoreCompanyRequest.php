<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
return Auth::check() && Auth::user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'max:255'],
            'logo'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = Auth::guard('api')->id();

        $noSpacesOrSpecialChars = 'regex:/^[\w\d]+$/';

        return [
            'name' => ['sometimes', 'string', 'min:4', 'max:150'],

            'username' => [
                'sometimes',
                'string',
                'min:3',
                'max:20',
                $noSpacesOrSpecialChars,
                Rule::unique('users', 'username')->ignore($userId),
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'password' => [
                'nullable',
                'string',
                'min:3',
                'max:20',
                $noSpacesOrSpecialChars
            ],

            'phone' => ['nullable', 'string', 'min:10', 'max:14', 'regex:/^\d{10,14}$/'],
            'experience' => ['nullable', 'string', 'min:10', 'max:600'],
            'education' => ['nullable', 'string', 'min:10', 'max:600'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge(['name' => strtoupper($this->name)]);
        }
    }
}
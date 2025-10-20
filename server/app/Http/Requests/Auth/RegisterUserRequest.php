<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Necessário para a regra 'unique'

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $this->merge([
            // Converte o campo 'name' para MAIÚSCULO
            'name' => strtoupper($this->name),
        ]);
    }


    public function rules()
    {
        $noSpacesOrSpecialChars = 'regex:/^[\w\d]+$/';

        return [
            'name' => ['required', 'string', 'min:4', 'max:150'],

            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                $noSpacesOrSpecialChars,
                Rule::unique('users', 'username'),
            ],

            'password' => [
                'required',
                'string',
                'min:3',
                'max:20',
                $noSpacesOrSpecialChars,
            ],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'min:10', 'max:14', 'regex:/^\d{10,14}$/'],
            'experience' => ['nullable', 'string', 'min:10', 'max:600'],
            'education' => ['nullable', 'string', 'min:10', 'max:600'],
        ];
    }


    public function messages()
    {
        return [
            // Mensagens para o campo 'name'
            'name.required' => 'O campo Nome é obrigatório.',
            'name.min' => 'O Nome deve ter no mínimo 4 caracteres.',
            'name.max' => 'O Nome deve ter no máximo 150 caracteres.',

            // Mensagens para o campo 'username'
            'username.required' => 'O campo Nome de Usuário é obrigatório.',
            'username.min' => 'O Nome de Usuário deve ter no mínimo 3 caracteres.',
            'username.max' => 'O Nome de Usuário deve ter no máximo 20 caracteres.',
            'username.unique' => 'Username already exists.',
            'username.regex' => 'O Nome de Usuário não deve conter espaços ou caracteres especiais.',

            // Mensagens para o campo 'password'
            'password.required' => 'O campo Senha é obrigatório.',
            'password.min' => 'A Senha deve ter no mínimo 3 caracteres.',
            'password.max' => 'A Senha deve ter no máximo 20 caracteres.',
            'password.regex' => 'A Senha não deve conter espaços ou caracteres especiais.',

            // Mensagens para o campo 'email'
            'email.email' => 'O Email deve ser um endereço de email válido.',

            // Mensagens para o campo 'phone'
            'phone.min' => 'O Telefone deve ter no mínimo 10 dígitos.',
            'phone.max' => 'O Telefone deve ter no máximo 14 dígitos.',
            'phone.regex' => 'O Telefone deve conter apenas dígitos.',

            // Mensagens para experience/education
            'experience.min' => 'A Experiência deve ter no mínimo 10 caracteres.',
            'experience.max' => 'A Experiência deve ter no máximo 600 caracteres.',
            'education.min' => 'A Educação deve ter no mínimo 10 caracteres.',
            'education.max' => 'A Educação deve ter no máximo 600 caracteres.',
        ];
    }
}
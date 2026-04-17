<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=> ['required', 'string', 'max:255'],
            'email'=> ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'=> ['required', 'confirmed', Password::min(8)],
            'role'=> ['required', 'string', 'in:' . implode(',', User::ROLES)],
            'division'=> ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        $roles = implode(', ', User::ROLES);

        return [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'role.required'     => 'Role wajib dipilih.',
            'role.in'           => "Role tidak valid. Pilih salah satu dari: {$roles}.",
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'Nama',
            'email'    => 'Email',
            'password' => 'Password',
            'role'     => 'Role',
            'division' => 'Divisi',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isDivision();
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul pengajuan wajib diisi.',
            'title.max'            => 'Judul pengajuan maksimal 255 karakter.',
            'description.required' => 'Deskripsi pengajuan wajib diisi.',
            'description.min'      => 'Deskripsi pengajuan minimal 10 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'Judul',
            'description' => 'Deskripsi',
        ];
    }
}

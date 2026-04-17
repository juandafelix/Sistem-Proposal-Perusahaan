<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $submission = $this->route('submission');
        return $this->user()
            && $this->user()->isDivision()
            && $submission
            && $submission->submitted_by === $this->user()->id
            && $submission->isPending();
    }
    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul pengajuan wajib diisi.',
            'title.max'            => 'Judul pengajuan maksimal 255 karakter.',
            'description.required'=> 'Deskripsi pengajuan wajib diisi.',
            'description.min'      => 'Deskripsi pengajuan minimal 10 karakter.',
        ];
    }
    public function attributes(): array
    {
        return [
            'title'      => 'Judul',
            'description'=> 'Deskripsi',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveSubmissionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user() && $this->user()->isManager();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:approved,rejected'],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status keputusan wajib diisi.',
            'status.in'       => 'Status hanya boleh: approved atau rejected.',
            'notes.max'       => 'Catatan maksimal 1000 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'Status Keputusan',
            'notes'  => 'Catatan',
        ];
    }
}

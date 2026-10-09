<?php

namespace App\Http\Requests\AppVersion;

use Illuminate\Foundation\Http\FormRequest;

class PublishAppVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'version'       => ['required', 'string', 'regex:/^v?\d+\.\d+\.\d+(-[0-9A-Za-z.-]+)?$/'],
            'release_name'  => ['nullable', 'string', 'max:255'],
            'changelog'     => ['nullable', 'string'],
            'update_guide'  => ['nullable', 'string'],
            'sync_local'    => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'version.required' => 'Nomor versi wajib diisi.',
            'version.regex'    => 'Format versi tidak valid. Gunakan format Semantic Versioning (contoh: 1.2.0 atau 1.2.1).',
            'release_name.max' => 'Nama rilis maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'version'      => 'Nomor Versi',
            'release_name' => 'Nama Rilis',
            'changelog'    => 'Catatan Perubahan',
            'update_guide' => 'Petunjuk Update',
            'sync_local'   => 'Sinkronkan Versi Lokal',
        ];
    }
}

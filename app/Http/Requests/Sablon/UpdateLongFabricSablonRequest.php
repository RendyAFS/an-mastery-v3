<?php

namespace App\Http\Requests\Sablon;

use App\Models\Sablon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateLongFabricSablonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'details'               => 'required|array|min:1',
            'details.*.id'          => 'required|integer|distinct',
            'details.*.long_fabric' => 'required|numeric|min:0',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $sablon = $this->route('sablon');

                if (! $sablon instanceof Sablon) {
                    return;
                }

                $ownedIds = $sablon->sablonDetails()->pluck('id')->map(fn($id) => (int) $id);

                foreach ($this->input('details', []) as $index => $row) {
                    if (! $ownedIds->contains((int) $row['id'])) {
                        $validator->errors()->add(
                            "details.{$index}.id",
                            __('sablon.validation.long_fabric_modal.id.invalid', ['row' => $index + 1])
                        );
                    }
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'details.required'               => __('sablon.validation.long_fabric_modal.details.required'),
            'details.min'                    => __('sablon.validation.long_fabric_modal.details.required'),
            'details.*.id.required'          => __('sablon.validation.long_fabric_modal.id.required'),
            'details.*.id.distinct'          => __('sablon.validation.long_fabric_modal.id.distinct'),
            'details.*.long_fabric.required' => __('sablon.validation.long_fabric_modal.long_fabric.required'),
            'details.*.long_fabric.numeric'  => __('sablon.validation.long_fabric_modal.long_fabric.numeric'),
            'details.*.long_fabric.min'      => __('sablon.validation.long_fabric_modal.long_fabric.min'),
        ];
    }
}

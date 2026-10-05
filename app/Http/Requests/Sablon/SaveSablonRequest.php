<?php

namespace App\Http\Requests\Sablon;

use App\Actions\Sablon\CalculateSablonAction;
use App\Helpers\RupiahHelper;
use App\Models\FabricDetail;
use App\Models\Sablon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SaveSablonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id'                           => 'required|exists:suppliers,id',
            'fabric_id'                             => 'required|exists:fabrics,id',
            'image_fabric_id'                       => 'required|exists:image_fabrics,id',
            'type_color_id'                         => 'required|exists:type_colors,id',
            'type_fabric_id'                        => 'required|exists:type_fabrics,id',
            'price_employee_id'                     => 'required|exists:price_employees,id',
            'total_long_fabric'                     => 'nullable|numeric|min:0',
            'total_sablon'                          => 'nullable|numeric|min:0',
            'date_sablon'                           => 'required|date',
            'status'                                => 'required|in:ON_PROGRESS,DONE,DELIVERED,RETURNED',
            'is_billed_in_advance'                  => 'nullable|boolean',
            'notes'                                 => 'nullable|string|max:255',
            // Fabric details (warna & panjang kain yang dipakai)
            'fabric_details'                        => 'required|array|min:1',
            'fabric_details.*.fabric_detail_id'     => 'required|exists:fabric_details,id',
            'fabric_details.*.color_fabric_id'      => 'required|exists:color_fabrics,id',
            'fabric_details.*.long_fabric'          => 'nullable|numeric|min:0',
            // Employee details (siapa yang mengerjakan & fee-nya)
            'employee_details'                             => 'nullable|array',
            'employee_details.*.id'                        => 'nullable|integer|exists:sablon_employee_details,id',
            'employee_details.*.employee_id'               => 'required_with:employee_details|exists:employees,id',
            'employee_details.*.layers'                    => 'nullable|integer|min:0',
            'employee_details.*.fee'                       => 'nullable|numeric',
            'employee_details.*.is_change'                 => 'nullable|boolean',
            'employee_details.*.employee_change_id'        => 'nullable|exists:employees,id|different:employee_details.*.employee_id',
            'employee_details.*.is_bon'                    => 'nullable|boolean',
            'employee_details.*.is_paid'                   => 'nullable|boolean',
            'employee_details.*.notes'                     => 'nullable|string|max:255',
            'employee_details.*.additional_fees'           => 'nullable|array',
            'employee_details.*.additional_fees.*.nominal' => 'nullable|numeric',
            'employee_details.*.additional_fees.*.notes'   => 'nullable|string|max:255',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->validateFabricDetails($validator);
                $this->validateEmployeeDetailIds($validator);

                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->validateCalculations($validator);
            },
        ];
    }

    public function sablon(): ?Sablon
    {
        $sablon = $this->route('sablon');

        return $sablon instanceof Sablon ? $sablon : null;
    }

    private function validateFabricDetails(Validator $validator): void
    {
        $rows    = $this->input('fabric_details', []);
        $details = FabricDetail::query()
            ->whereIn('id', collect($rows)->pluck('fabric_detail_id')->filter()->unique())
            ->get(['id', 'fabric_id', 'color_fabric_id'])
            ->keyBy('id');

        foreach ($rows as $index => $row) {
            $detail = $details->get($row['fabric_detail_id'] ?? null);
            if (! $detail) {
                continue;
            }

            if ((int) $detail->fabric_id !== (int) $this->input('fabric_id')) {
                $validator->errors()->add(
                    "fabric_details.{$index}.fabric_detail_id",
                    __('sablon.validation.fabric_details.fabric_detail_id.not_belongs_to_fabric', ['row' => $index + 1])
                );
            }

            if ((int) $detail->color_fabric_id !== (int) ($row['color_fabric_id'] ?? 0)) {
                $validator->errors()->add(
                    "fabric_details.{$index}.color_fabric_id",
                    __('sablon.validation.fabric_details.color_fabric_id.mismatch', ['row' => $index + 1])
                );
            }
        }
    }

    private function validateEmployeeDetailIds(Validator $validator): void
    {
        $rows = collect($this->input('employee_details', []));
        $ids  = $rows->pluck('id')->filter()->map(fn($id) => (int) $id);

        if ($ids->isEmpty()) {
            return;
        }

        $sablon   = $this->sablon();
        $ownedIds = $sablon
            ? $sablon->sablonEmployeeDetails()->whereIn('id', $ids)->pluck('id')->map(fn($id) => (int) $id)
            : collect();

        foreach ($rows as $index => $row) {
            $id = isset($row['id']) ? (int) $row['id'] : null;

            if ($id && ! $ownedIds->contains($id)) {
                $validator->errors()->add(
                    "employee_details.{$index}.id",
                    __('sablon.validation.employee_details.id.invalid', ['row' => $index + 1])
                );
            }
        }
    }

    private function validateCalculations(Validator $validator): void
    {
        $calculator = app(CalculateSablonAction::class);
        $result     = $calculator->handle($this->all(), $this->sablon());

        if ($this->filled('total_long_fabric') && $calculator->isMismatch($this->input('total_long_fabric'), $result['total_long_fabric'])) {
            $validator->errors()->add('total_long_fabric', __('sablon.validation.total_long_fabric.mismatch', [
                'expected' => $result['total_long_fabric'],
            ]));
        }

        if ($this->filled('total_sablon') && $calculator->isMismatch($this->input('total_sablon'), $result['total_sablon'])) {
            $validator->errors()->add('total_sablon', __('sablon.validation.total_sablon.mismatch', [
                'expected' => RupiahHelper::format($result['total_sablon']),
            ]));
        }

        foreach ($this->input('employee_details', []) as $index => $row) {
            $calc = $result['employee_fees'][$index] ?? null;

            if (! $calc || $calc['locked'] || ! array_key_exists('fee', $row)) {
                continue;
            }

            if ($calculator->isMismatch($row['fee'], $calc['fee'])) {
                $validator->errors()->add("employee_details.{$index}.fee", __('sablon.validation.employee_details.fee.mismatch', [
                    'row'      => $index + 1,
                    'expected' => RupiahHelper::format($calc['fee']),
                ]));
            }
        }
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'                            => __('sablon.validation.supplier_id.required'),
            'supplier_id.exists'                              => __('sablon.validation.supplier_id.exists'),
            'fabric_id.required'                              => __('sablon.validation.fabric_id.required'),
            'fabric_id.exists'                                => __('sablon.validation.fabric_id.exists'),
            'image_fabric_id.required'                        => __('sablon.validation.image_fabric_id.required'),
            'image_fabric_id.exists'                          => __('sablon.validation.image_fabric_id.exists'),
            'type_color_id.required'                          => __('sablon.validation.type_color_id.required'),
            'type_color_id.exists'                            => __('sablon.validation.type_color_id.exists'),
            'type_fabric_id.required'                         => __('sablon.validation.type_fabric_id.required'),
            'type_fabric_id.exists'                           => __('sablon.validation.type_fabric_id.exists'),
            'price_employee_id.required'                      => __('sablon.validation.price_employee_id.required'),
            'price_employee_id.exists'                        => __('sablon.validation.price_employee_id.exists'),
            'date_sablon.required'                            => __('sablon.validation.date_sablon.required'),
            'date_sablon.date'                                => __('sablon.validation.date_sablon.date'),
            'status.required'                                 => __('sablon.validation.status.required'),
            'status.in'                                       => __('sablon.validation.status.in'),
            // Fabric Detail
            'fabric_details.required'                         => __('sablon.validation.fabric_details.required'),
            'fabric_details.*.fabric_detail_id.required'      => __('sablon.validation.fabric_details.fabric_detail_id.required'),
            'fabric_details.*.fabric_detail_id.exists'        => __('sablon.validation.fabric_details.fabric_detail_id.exists'),
            'fabric_details.*.color_fabric_id.required'       => __('sablon.validation.fabric_details.color_fabric_id.required'),
            'fabric_details.*.color_fabric_id.exists'         => __('sablon.validation.fabric_details.color_fabric_id.exists'),
            // Employee Detail
            'employee_details.*.employee_id.required_with'    => __('sablon.validation.employee_details.employee_id.required_with'),
            'employee_details.*.employee_id.exists'           => __('sablon.validation.employee_details.employee_id.exists'),
            'employee_details.*.employee_change_id.exists'    => __('sablon.validation.employee_details.employee_change_id.exists'),
            'employee_details.*.employee_change_id.different' => __('sablon.validation.employee_details.employee_change_id.different'),
            'employee_details.*.notes.max'                    => __('sablon.validation.employee_details.notes.max'),
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id'                           => __('sablon.form.supplier'),
            'fabric_id'                             => __('sablon.form.fabric'),
            'image_fabric_id'                       => __('sablon.form.image_fabric'),
            'type_color_id'                         => __('sablon.form.type_color'),
            'type_fabric_id'                        => __('sablon.form.type_fabric'),
            'price_employee_id'                     => __('sablon.form.price_employee'),
            'total_long_fabric'                     => __('sablon.form.total_long_fabric'),
            'total_sablon'                          => __('sablon.form.total_sablon'),
            'date_sablon'                           => __('sablon.form.date_sablon'),
            'status'                                => __('sablon.form.status'),
            'notes'                                 => __('sablon.form.notes'),
            // Fabric Detail
            'fabric_details'                        => __('sablon.form.fabric_details'),
            'fabric_details.*.fabric_detail_id'     => __('sablon.form.fabric_detail'),
            'fabric_details.*.color_fabric_id'      => __('sablon.form.color_fabric'),
            'fabric_details.*.long_fabric'          => __('sablon.form.long_fabric'),
            // Employee Detail
            'employee_details'                      => __('sablon.form.employee_details'),
            'employee_details.*.employee_id'        => __('sablon.form.employee'),
            'employee_details.*.layers'             => __('sablon.form.layers'),
            'employee_details.*.fee'                => __('sablon.form.fee'),
            'employee_details.*.employee_change_id' => __('sablon.form.employee_change'),
            'employee_details.*.is_bon'             => __('sablon.employee_detail.is_bon'),
            'employee_details.*.is_paid'            => __('sablon.employee_detail.is_paid'),
            'employee_details.*.notes'              => __('sablon.form.notes'),
        ];
    }
}

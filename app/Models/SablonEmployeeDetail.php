<?php

namespace App\Models;

use App\Enums\StatusSablonEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SablonEmployeeDetail extends Model
{
    protected $fillable = [
        'sablon_id',
        'employee_id',
        'salary_employee_id',
        'layers',
        'fee',
        'is_change',
        'employee_change_id',
        'is_bon',
        'is_paid',
        'notes'
    ];

    protected $casts = [
        'is_change' => 'boolean',
        'is_bon'    => 'boolean',
        'is_paid'  => 'boolean',
    ];

    public function sablon(): BelongsTo
    {
        return $this->belongsTo(Sablon::class, 'sablon_id');
    }

    public function fabricDetail(): BelongsTo
    {
        return $this->belongsTo(FabricDetail::class, 'fabric_detail_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employeeChange(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_change_id');
    }

    public function salaryEmployee(): BelongsTo
    {
        return $this->belongsTo(SalaryEmployee::class, 'salary_employee_id');
    }

    public function scopeEligibleForSalary(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where('is_paid', false)
                ->orWhereNull('is_paid');
        })
            ->where(function (Builder $q) {
                $q->where(function (Builder $q1) {
                    $q1->where('is_bon', false)
                        ->whereHas('sablon', fn($s) => $s->whereIn('status', [
                            StatusSablonEnum::DONE,
                            StatusSablonEnum::DELIVERED,
                        ]));
                })->orWhere('is_bon', true);
            });
    }

    public function isEligibleForSalary(): bool
    {
        if ($this->is_paid) {
            return false;
        }

        if ($this->is_bon) {
            return true;
        }

        return in_array($this->sablon?->status, [
            StatusSablonEnum::DONE,
            StatusSablonEnum::DELIVERED,
        ]);
    }
}

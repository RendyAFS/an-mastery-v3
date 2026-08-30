<?php

namespace App\Models;

use App\Enums\StatusSablonEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SablonEmployeeDetail extends Model
{
    protected $fillable = [
        'sablon_id',
        'employee_id',
        'salary_employee_id',
        'layers',
        'fee',
        'additional_fee',
        'is_change',
        'employee_change_id',
        'is_bon',
        'is_paid',
        'is_settled',
        'settled_at',
        'settlement_of_id',
        'late_eligible_at',
        'notes'
    ];

    protected $casts = [
        'additional_fee'   => 'array',
        'is_change'        => 'boolean',
        'is_bon'           => 'boolean',
        'is_paid'          => 'boolean',
        'is_settled'       => 'boolean',
        'settled_at'       => 'date',
        'late_eligible_at' => 'date',
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

    public function settlementOf(): BelongsTo
    {
        return $this->belongsTo(SablonEmployeeDetail::class, 'settlement_of_id');
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(SablonEmployeeDetail::class, 'settlement_of_id');
    }

    public function scopeEligibleForSalary(Builder $query): Builder
    {
        return $query->where('is_settled', false)
            ->where(function (Builder $q) {
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

    public function scopeOpenInProgress(Builder $query): Builder
    {
        return $query->whereNull('salary_employee_id')
            ->where('is_settled', false)
            ->whereNull('settlement_of_id')
            ->whereHas('sablon', fn($s) => $s->where('status', StatusSablonEnum::ON_PROGRESS));
    }

    public function isEligibleForSalary(): bool
    {
        if ($this->is_settled) {
            return false;
        }

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

    public function countableAmount(): float
    {
        $additionalFeeSum = collect($this->additional_fee ?? [])
            ->sum(fn($af) => (float) ($af['nominal'] ?? 0));

        if ($this->is_bon) {
            if ($this->is_settled) {
                return $additionalFeeSum;
            }

            if (! in_array($this->sablon?->status, [
                StatusSablonEnum::DONE,
                StatusSablonEnum::DELIVERED,
            ])) {
                return $additionalFeeSum;
            }
        }

        return (float) $this->fee + $additionalFeeSum;
    }

    public function scopeInWeek(Builder $query, string $start, string $end): Builder
    {
        return $query->where(function (Builder $q) use ($start, $end) {
            $q->where(function (Builder $q1) use ($start, $end) {
                $q1->whereNull('settlement_of_id')
                    ->whereNull('late_eligible_at')
                    ->where('is_bon', false)
                    ->whereHas('sablon', fn($s) => $s->whereBetween('date_sablon', [$start, $end]));
            })->orWhere(function (Builder $q1) use ($start, $end) {
                $q1->whereNull('settlement_of_id')
                    ->whereNull('late_eligible_at')
                    ->where('is_bon', true)
                    ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
            })->orWhere(function (Builder $q1) use ($start, $end) {
                $q1->whereNotNull('settlement_of_id')
                    ->whereBetween('settled_at', [$start, $end]);
            })->orWhere(function (Builder $q1) use ($start, $end) {
                $q1->whereNull('settlement_of_id')
                    ->whereNotNull('late_eligible_at')
                    ->whereBetween('late_eligible_at', [$start, $end]);
            });
        });
    }

    public function weekAnchorDate(): ?string
    {
        if ($this->settlement_of_id) {
            return $this->settled_at?->toDateString();
        }

        if ($this->late_eligible_at) {
            return $this->late_eligible_at->toDateString();
        }

        if ($this->is_bon) {
            return $this->created_at?->toDateString();
        }

        return $this->sablon?->date_sablon?->toDateString();
    }
}

<?php

namespace App\Models;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Helpers\SalaryBonusHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class SalaryEmployee extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'fee',
        'additional_fee',
        'status',
        'date',
        'notes',
    ];

    protected $casts = [
        'additional_fee' => 'array',
        'status'         => StatusSalaryEmployeeEnum::class,
        'date'           => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function sablonEmployeeDetails(): HasMany
    {
        return $this->hasMany(SablonEmployeeDetail::class, 'salary_employee_id');
    }

    public function presence(): HasOne
    {
        return $this->hasOne(Presence::class, 'employee_id', 'employee_id')
            ->where('week_of', $this->date);
    }

    public function memos(): HasMany
    {
        return $this->hasMany(Memo::class, 'salary_employee_id');
    }

    public function computedTotal(): float
    {
        $sablonFeeTotal = $this->sablonEmployeeDetails
            ->filter(fn($detail) => $detail->salary_employee_id !== null || $detail->isEligibleForSalary())
            ->sum(fn($detail) => $detail->countableAmount());

        $presenceTotal = (float) ($this->presence?->total ?? 0);
        $fees = $this->additional_fee ?? [];
        $hasBonus = collect($fees)->contains(fn($af) => mb_strtolower(trim($af['notes'] ?? '')) === 'bonus');

        $bonusToAdd = 0;
        if (! $hasBonus && $this->status !== StatusSalaryEmployeeEnum::PAID) {
            $isFabric = fn($af) => str_starts_with(mb_strtolower(trim($af['notes'] ?? '')), 'plus kain')
                || str_starts_with(mb_strtolower(trim($af['notes'] ?? '')), 'minus kain');
            $fabricAdjustmentTotal = collect($fees)->filter($isFabric)->sum(fn($af) => (float) ($af['nominal'] ?? 0));
            $totalSablon = $sablonFeeTotal + $fabricAdjustmentTotal;
            $bonusToAdd = SalaryBonusHelper::calculateBonus($totalSablon);
        }

        $additionalFeeTotal = collect($fees)->sum(fn($af) => (float) ($af['nominal'] ?? 0)) + $bonusToAdd;
        $memoTotal = $this->memos->sum('nominal');

        return $sablonFeeTotal + $presenceTotal + $additionalFeeTotal + $memoTotal;
    }
}

<?php

namespace App\Models;

use App\Enums\StatusSalaryEmployeeEnum;
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
        $additionalFeeTotal = collect($this->additional_fee ?? [])->sum(fn($af) => (float) ($af['nominal'] ?? 0));
        $memoTotal = $this->memos->sum('nominal');

        return $sablonFeeTotal + $presenceTotal + $additionalFeeTotal + $memoTotal;
    }
}

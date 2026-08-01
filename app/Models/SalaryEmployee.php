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
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Presence extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'week_of',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'total',
        'notes',
    ];

    protected $casts = [
        'week_of'   => 'date',
        'monday'    => 'integer',
        'tuesday'   => 'integer',
        'wednesday' => 'integer',
        'thursday'  => 'integer',
        'friday'    => 'integer',
        'saturday'  => 'integer',
        'sunday'    => 'integer',
        'total'     => 'integer',
    ];

    protected array $dayOrder = [
        'monday' => 0,
        'tuesday' => 1,
        'wednesday' => 2,
        'thursday' => 3,
        'friday' => 4,
        'saturday' => 5,
        'sunday' => 6,
    ];

    public function dateFor(string $day): Carbon
    {
        return $this->week_of->copy()->addDays($this->dayOrder[$day] ?? 0);
    }

    /**
     * @return array<string, string> day => 'Y-m-d'
     */
    public function dayDates(): array
    {
        return collect($this->dayOrder)
            ->map(fn($offset) => $this->week_of->copy()->addDays($offset)->toDateString())
            ->toArray();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

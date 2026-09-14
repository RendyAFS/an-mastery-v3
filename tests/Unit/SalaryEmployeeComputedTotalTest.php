<?php

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\SablonEmployeeDetail;
use App\Models\SalaryEmployee;
use Illuminate\Database\Eloquent\Collection;

uses(Tests\TestCase::class);

test('computedTotal includes bonus automatically if pending and no bonus fee present', function () {
    $salary = new SalaryEmployee([
        'id'             => 1,
        'status'         => StatusSalaryEmployeeEnum::PENDING,
        'additional_fee' => [],
    ]);

    $detail1 = new SablonEmployeeDetail(['fee' => 120000, 'salary_employee_id' => 1]);
    $detail2 = new SablonEmployeeDetail(['fee' => 90000, 'salary_employee_id' => 1]);
    // total = 210.000 => tier is 10.000 bonus

    $salary->setRelation('sablonEmployeeDetails', new Collection([$detail1, $detail2]));
    $salary->setRelation('memos', new Collection([]));

    // Sablon 210.000 + Bonus 10.000 = 220.000
    expect($salary->computedTotal())->toBe(220000.0);
});

test('computedTotal does not double add bonus if bonus already in additional_fee', function () {
    $salary = new SalaryEmployee([
        'id'             => 1,
        'status'         => StatusSalaryEmployeeEnum::PENDING,
        'additional_fee' => [
            ['nominal' => 10000, 'notes' => 'Bonus'],
        ],
    ]);

    $detail1 = new SablonEmployeeDetail(['fee' => 120000, 'salary_employee_id' => 1]);
    $detail2 = new SablonEmployeeDetail(['fee' => 90000, 'salary_employee_id' => 1]);

    $salary->setRelation('sablonEmployeeDetails', new Collection([$detail1, $detail2]));
    $salary->setRelation('memos', new Collection([]));

    // Sablon 210.000 + Bonus 10.000 = 220.000 (not 230.000)
    expect($salary->computedTotal())->toBe(220000.0);
});

test('computedTotal does not add bonus if status is PAID and not in additional_fee', function () {
    $salary = new SalaryEmployee([
        'id'             => 1,
        'status'         => StatusSalaryEmployeeEnum::PAID,
        'additional_fee' => [],
    ]);

    $detail1 = new SablonEmployeeDetail(['fee' => 120000, 'salary_employee_id' => 1]);
    $detail2 = new SablonEmployeeDetail(['fee' => 90000, 'salary_employee_id' => 1]);

    $salary->setRelation('sablonEmployeeDetails', new Collection([$detail1, $detail2]));
    $salary->setRelation('memos', new Collection([]));

    expect($salary->computedTotal())->toBe(210000.0);
});

test('previous week unpaid salary carries over with bonus into current week resource total', function () {
    // Week 1: sablon 200.000 + bonus 10.000 = 210.000 (status PENDING)
    $prevSalary = new SalaryEmployee([
        'id'             => 10,
        'date'           => '2026-09-01',
        'status'         => StatusSalaryEmployeeEnum::PENDING,
        'additional_fee' => [],
    ]);
    $prevDetail = new SablonEmployeeDetail(['fee' => 200000, 'salary_employee_id' => 10]);
    $prevSalary->setRelation('sablonEmployeeDetails', new Collection([$prevDetail]));
    $prevSalary->setRelation('memos', new Collection([]));

    // Week 2: sablon 200.000 + bonus 10.000 = 210.000 (status PENDING)
    $currentSalary = new SalaryEmployee([
        'id'             => 20,
        'date'           => '2026-09-08',
        'status'         => StatusSalaryEmployeeEnum::PENDING,
        'additional_fee' => [],
    ]);
    $currDetail = new SablonEmployeeDetail(['fee' => 200000, 'salary_employee_id' => 20]);
    $currentSalary->setRelation('sablonEmployeeDetails', new Collection([$currDetail]));
    $currentSalary->setRelation('memos', new Collection([]));
    $currentSalary->setRelation('previousPendingSalaries', new Collection([$prevSalary]));

    $resource = (new \App\Http\Resources\SalaryEmployeeResource($currentSalary))->toArray(request());

    // Current week total sablon = 200.000
    expect($resource['fee'])->toBe(200000.0);
    // Current week auto bonus = 10.000
    expect($resource['additional_fee_total'])->toBe(10000.0);
    // Previous week total (including its 10.000 bonus) = 210.000
    expect($resource['previous_week_fees_total'])->toBe(210000.0);
    // Combined total = 200.000 + 10.000 + 210.000 = 420.000
    expect($resource['total'])->toBe(420000.0);
});

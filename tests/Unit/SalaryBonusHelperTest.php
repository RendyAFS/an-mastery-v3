<?php

use App\Helpers\SalaryBonusHelper;

test('salary bonus helper calculates correct bonus for all tiers', function () {
    expect(SalaryBonusHelper::calculateBonus(0))->toBe(0);
    expect(SalaryBonusHelper::calculateBonus(199999))->toBe(0);

    // 200.000 - 249.999 => 10.000
    expect(SalaryBonusHelper::calculateBonus(200000))->toBe(10000);
    expect(SalaryBonusHelper::calculateBonus(225000))->toBe(10000);
    expect(SalaryBonusHelper::calculateBonus(249999))->toBe(10000);

    // 250.000 - 399.999 => 15.000
    expect(SalaryBonusHelper::calculateBonus(250000))->toBe(15000);
    expect(SalaryBonusHelper::calculateBonus(300000))->toBe(15000);
    expect(SalaryBonusHelper::calculateBonus(399999))->toBe(15000);

    // 400.000 - 499.999 => 20.000
    expect(SalaryBonusHelper::calculateBonus(400000))->toBe(20000);
    expect(SalaryBonusHelper::calculateBonus(450000))->toBe(20000);
    expect(SalaryBonusHelper::calculateBonus(499999))->toBe(20000);

    // 500.000+ => 25.000
    expect(SalaryBonusHelper::calculateBonus(500000))->toBe(25000);
    expect(SalaryBonusHelper::calculateBonus(750000))->toBe(25000);
    expect(SalaryBonusHelper::calculateBonus(1000000))->toBe(25000);
});

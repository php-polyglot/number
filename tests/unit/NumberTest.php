<?php

declare(strict_types=1);

namespace TestUnits\Polyglot\Number;

use PHPUnit\Framework\TestCase;
use Polyglot\Number\Number;

final class NumberTest extends TestCase
{
    /**
     * @param string|int|float $value
     * @param int|float $n
     * @param int $i
     * @param int $v
     * @param int $w
     * @param int $f
     * @param int $t
     * @param int $c
     * @return void
     * @dataProvider provideOk
     */
    public function testOk($value, $n, int $i, int $v, int $w, int $f, int $t, int $c): void
    {
        $number = Number::create($value);
        $this->assertSame($n, $number->number());
        $this->assertSame($i, $number->integer());
        $this->assertSame($v, $number->fractionDigits());
        $this->assertSame($w, $number->fractionDigits(false));
        $this->assertSame($f, $number->fraction());
        $this->assertSame($t, $number->fraction(false));
        $this->assertSame($c, $number->exponent());
    }

    public function provideOk(): iterable
    {
        return [
            [0, 0, 0, 0, 0, 0, 0, 0],
            ['0', 0, 0, 0, 0, 0, 0, 0],
            [0.0, 0.0, 0, 1, 0, 0, 0, 0],
            ['0.0', 0.0, 0, 1, 0, 0, 0, 0],
            [1, 1, 1, 0, 0, 0, 0, 0],
            ['1', 1, 1, 0, 0, 0, 0, 0],
            ['1.0', 1.0, 1, 1, 0, 0, 0, 0],
            [1.0, 1.0, 1, 1, 0, 0, 0, 0],
            ['1.00', 1.0, 1, 2, 0, 0, 0, 0],
            ['1.3', 1.3, 1, 1, 1, 3, 3, 0],
            ['0.3', 0.3, 0, 1, 1, 3, 3, 0],
            ['.3', 0.3, 0, 1, 1, 3, 3, 0],
            [.3, 0.3, 0, 1, 1, 3, 3, 0],
            ['1.30', 1.3, 1, 2, 1, 30, 3, 0],
            ['1.03', 1.03, 1, 2, 2, 3, 3, 0],
            ['1.230', 1.23, 1, 3, 2, 230, 23, 0],
            [1.23, 1.23, 1, 2, 2, 23, 23, 0],
            [1.230, 1.23, 1, 2, 2, 23, 23, 0],
            ['1200000', 1200000, 1200000, 0, 0, 0, 0, 0],
            ['123c6', 123000000, 123000000, 0, 0, 0, 0, 6],
            ['123c5', 12300000, 12300000, 0, 0, 0, 0, 5],
            ['1200.50', 1200.5, 1200, 2, 1, 50, 5, 0],
            ['1.20050c3', 1200.5, 1200, 2, 1, 50, 5, 3],
            ['1.20050c7', 12005000, 12005000, 0, 0, 0, 0, 7],
        ];
    }
}

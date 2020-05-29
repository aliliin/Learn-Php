<?php

use PHPUnit\Framework\TestCase;

class DivisionTest extends TestCase
{
    /** @test */
    public function divides_given_operands()
    {
        $division = new \App\Calculator\Division;
        $division->setOperands([100, 2]);

        $this->assertEquals(50, $division->calculate());
    }
    /** @test */
    public function no_operands_given_throws_exception_when_calculationg()
    {
        $this->expectException(\App\Calculator\Exceptions\NoOperandsException::class);

        $addition = new \App\Calculator\Division;

        $addition->calculate();
    }

    /** @test */

    public function removes_division_by_zero_operands()
    {
        $division = new \App\Calculator\Division;
        $division->setOperands([10, 0, 0, 0, 2]);

        $this->assertEquals(5, $division->calculate());
    }
}

<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /** @test */
    public function can_set_single_operation()
    {
        $addition = new \App\Calculator\Addition;
        $addition->setOperands([5, 10]);

        $calculator = new \App\Calculator\Calculator;
        $calculator->setOperation($addition);

        $this->assertCount(1, $calculator->getOperations());
    }


    /** @test */
    public function can_set_multiple_operations()
    {
        $addition = new \App\Calculator\Addition;
        $addition->setOperands([5, 10]);

        $addition1 = new \App\Calculator\Addition;
        $addition1->setOperands([2, 2]);


        $calculator = new \App\Calculator\Calculator;
        $calculator->setOperations([$addition, $addition1]);

        $this->assertCount(2, $calculator->getOperations());
    }

    /** @test*/
    public function operations_are_ignored_if_not_instance_of_operation_interface()
    {
        $addition = new \App\Calculator\Addition;
        $addition->setOperands([5, 10]);

        $calculator = new \App\Calculator\Calculator;
        $calculator->setOperations([$addition, 'cats']);
        $this->assertCount(1, $calculator->getOperations());
    }

    /** @test */
    public function can_calculate_result()
    {
        $addition = new \App\Calculator\Addition;
        $addition->setOperands([5, 10]);

        $calculator = new \App\Calculator\Calculator;
        $calculator->setOperation($addition);

        $this->assertEquals(15, $calculator->calculate());
    }

    /** @test */
    public function calculate_method_returns_multiple_results()
    {
        $addition = new \App\Calculator\Addition;
        $addition->setOperands([5, 10]); // 15

        $division = new \App\Calculator\Division;
        $division->setOperands([50, 2]); // 25

        $calculator = new \App\Calculator\Calculator;
        $calculator->setOperations([$addition, $division]);

        $this->assertIsArray($calculator->calculate());
        $this->assertEquals(15, $addition->calculate());
        $this->assertEquals(25, $division->calculate());
    }
}

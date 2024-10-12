<?php
// tests/Unit/CalculatorTest.php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        $result = 2 + 3;
        $this->assertEquals(5, $result);
    }
}

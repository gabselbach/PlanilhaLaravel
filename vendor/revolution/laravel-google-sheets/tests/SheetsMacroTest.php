<?php

namespace Tests;

use Revolution\Google\Sheets\Facades\Sheets;

class SheetsMacroTest extends TestCase
{
    public function testMacro()
    {
        Sheets::macro('test', function () {
            return 'test';
        });

        $test = Sheets::test();

        $this->assertTrue(Sheets::hasMacro('test'));
        $this->assertTrue(is_callable(Sheets::class, 'test'));
        $this->assertSame('test', $test);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testMacroException()
    {
        $test = Sheets::test2();
    }
}

<?php

namespace Lfgscavelli\Todolist\Test\Unit;

use Lfgscavelli\Todolist\Todolist;
use Lfgscavelli\Todolist\Test\TestCase;

class TestTodolist extends TestCase
{

    public function testAddition()
    {
        $result = (new Todolist)->add(17, 3);
        $this->assertEquals(20, $result);
        // oppure
        $this->assertSame((new Todolist)->add(2, 9), 11);
    }

    public function testmodel() {

        $this->assertTrue(true);
    }
}
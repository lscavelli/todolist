<?php

namespace Lfgscavelli\Todolist\Test;

use Lfgscavelli\Todolist\Task;
use Tests\TestCase;

class TodolistTest extends TestCase
{
    public function testAddition()
    {
        $result = Todolist::add(17, 3);
        $this->assertEquals(20, $result);
        // oppure
        $this->assertSame(Todolist::add(2, 9), 11);
    }
}
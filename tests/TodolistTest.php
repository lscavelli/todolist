<?php

namespace Lfgscavelli\Todolist\Test;

use Lfgscavelli\Todolist\Task;

class TodolistTest extends TestCase
{
    public function testAddition()
    {
        $result = Todolist::add(17, 3);

        $this->assertEquals(20, $result);
    }
}
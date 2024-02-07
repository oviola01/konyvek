<?php

namespace Tests\Unit;

use App\Room;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_is_Zsolt_in_the_room(): void
    {
        $people = new Room(['Zsolt']);
        $this->assertTrue($people->has('Zsolt'));
    }
}

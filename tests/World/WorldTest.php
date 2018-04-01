<?php

namespace Mdojr\Scraper\World;

use Mdojr\Scraper\World\World;
use Mdojr\Scraper\Exception\InvalidWorldException;
use PHPUnit\Framework\TestCase;

class WorldTest extends TestCase
{

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(World::class, new World(World::FIDERA));
    }

    public function testThrowInvalidWorldException()
    {
        $this->expectException(InvalidWorldException::class);
        new World('invalid world');
    }

    public function testCanCreateCorrectInstance()
    {
        $world = World::ZUNERA;
        $worldObj = new World($world);
        $this->assertEquals($world, $worldObj->getWorld());
    }

    public function testCanGetAllWorlds()
    {
        $this->assertTrue(is_array(World::getAllWorlds()));
    }
}
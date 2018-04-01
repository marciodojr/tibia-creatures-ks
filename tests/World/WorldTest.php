<?php

namespace Mdojr\Scraper\World;

use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\World\World;
use Mdojr\Scraper\Exception\InvalidWorldException;

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
}
<?php

namespace Mdojr\Scraper\World;

use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\World\World;
use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\Exception\InvalidWorldException;

class WorldArrayTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(WorldArray::class, new WorldArray([]));
        $this->assertInstanceOf(WorldArray::class, new WorldArray([
            new World(World::BELOBRA),
            new World(World::FEROBRA),
            new World(World::LUMINERA)
        ]));
    }

    public function testThrowInvalidWorldExceptionOnCreate()
    {
        $this->expectException(InvalidWorldException::class);
        new WorldArray([new World(World::PACERA), 'Fidebrazil']);
    }

    public function testThrowInvalidWorldExceptionOnCreateWithValidWorldConst()
    {
        $this->expectException(InvalidWorldException::class);
        new WorldArray([new World(World::PACERA), World::FIDERA]);
    }

    public function testThrowInvalidWorldExceptionOnAppend()
    {
        $worlds = new WorldArray([]);
        $worlds->append(new World(World::PACERA));

        $this->expectException(InvalidWorldException::class);
        $worlds->append('invalid world');
    }

    public function testThrowInvalidWorldExceptionOnAppendValidWorldConst()
    {
        $worlds = new WorldArray([]);

        $this->expectException(InvalidWorldException::class);
        $worlds->append(World::PACERA);
    }

    public function testThrowInvalidWorldExceptionOnExchangeArray()
    {
        $worlds = [new World(World::ZANERA), new World(World::ZUNA)];
        $anotherWorlds = [World::AMERA];
        $worldArr = new WorldArray($worlds);

        $this->expectException(InvalidWorldException::class);
        $worldArr->exchangeArray($anotherWorlds);
    }

    public function testCanExchangeArray()
    {
        $worlds = [new World(World::ZANERA), new World(World::ZUNA)];
        $anotherWorlds = [new World(World::LAUDERA), new World(World::ANTICA), new World(World::FORTERA)];
        $worldArr = new WorldArray($worlds);
        
        $this->assertSame($worlds, $worldArr->exchangeArray($anotherWorlds));
        $this->assertSame($anotherWorlds, $worldArr->exchangeArray($worlds));
    }

    public function testCanUseOffsetSet()
    {
        $worlds = [new World(World::ZANERA), new World(World::ZUNA)];
        $worldsArr = new WorldArray($worlds);
        $anotherWorld = new World(World::HONBRA);

        $this->assertSame($worlds[0], $worldsArr->offsetGet(0));
        
        $worldsArr->offsetSet(0, $anotherWorld);        

        $this->assertSame($anotherWorld, $worldsArr->offsetGet(0));
        $this->assertNotEquals($worlds[0], $worldsArr->offsetGet(0));
    }

    public function testOffsetSetThrowsInvalidWorldException()
    {
        $worlds = [new World(World::ZANERA), new World(World::ZUNA)];
        $anotherWorld = World::AMERA;
        $worldArr = new WorldArray($worlds);

        $this->expectException(InvalidWorldException::class);
        $worldArr->offsetSet(0, $anotherWorld);
    }
}
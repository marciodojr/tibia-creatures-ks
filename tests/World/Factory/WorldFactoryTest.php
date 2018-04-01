<?php

namespace Mdojr\Scraper\World\Factory;

use Mdojr\Scraper\World\Factory\WorldFactory;
use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\World;
use PHPUnit\Framework\TestCase;

class WorldFactoryTest extends TestCase
{
    public function testCanCreateAll()
    {
        $worldArray = WorldFactory::createAll();
        $this->assertInstanceOf(WorldArray::class, $worldArray);

        foreach($worldArray as $world) {
            $this->assertInstanceOf(World::class, $world);
        }

        $this->assertEquals(count(World::getAllWorlds()), $worldArray->count());
    }

}
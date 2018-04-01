<?php

namespace Mdojr\Scraper;

use Mdojr\Scraper\WorldScraper;
use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\WorldResultArray;
use Mdojr\Scraper\World\World;

class WorldScraperTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $worlds = new WorldArray([]);
        $this->assertInstanceOf(WorldScraper::class, new WorldScraper($worlds));

        $otherWorlds = new WorldArray([
            new World(World::FIDERA),
            new World(World::PACERA)
        ]);
        $this->assertInstanceOf(WorldScraper::class, new WorldScraper($otherWorlds));
    }

    public function testCanFetchWorldResult()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $ws = new WorldScraper($worlds);
        $result = $ws->fetch();
        $this->assertInstanceOf(WorldResultArray::class, $result);
    }

    public function testCanDoMultipleFetchs()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $ws = new WorldScraper($worlds);
        $result1 = $ws->fetch();        
        $this->assertInstanceOf(WorldResultArray::class, $result1);
        $result2 = $ws->fetch();
        $this->assertInstanceOf(WorldResultArray::class, $result2);
        $this->assertNotEquals($result1, $result2);
    }


    public function testCanFetchAllWorldResults()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $ws = new WorldScraper($worlds);
        $results = $ws->fetchAll();

        $this->assertTrue(is_array($results));
        foreach($results as $r) {
            $this->assertInstanceOf(WorldResultArray::class, $r);
        }

        var_dump($results);
    }
}

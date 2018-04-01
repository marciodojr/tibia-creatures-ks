<?php

namespace Mdojr\Scraper\World;

use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\World\WorldResult;

class WorldResultTest extends TestCase
{

    public function testCanCreateInstance()
    {
        $creture = 'Skeleton';
        $killedPlayers = 2;
        $killedByPlayers = 1000;

        $this->assertInstanceOf(WorldResult::class, new WorldResult($creture, $killedPlayers, $killedByPlayers));
    }

    public function testCanCreateCorrectInstance()
    {
        $creture = 'Dragon';
        $killedPlayers = 10;
        $killedByPlayers = 2000;

        $resObj = new WorldResult($creture, $killedPlayers, $killedByPlayers);
        
        $this->assertEquals($creture, $resObj->creature);
        $this->assertEquals($killedPlayers, $resObj->killedPlayers);
        $this->assertEquals($killedByPlayers, $resObj->killedByPlayers);
    }
}
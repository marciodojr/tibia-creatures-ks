<?php

namespace Mdojr\Scraper\Exception;

use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\Exception\WorldNotLoadedException;
use Exception;

class WorldNotLoadedExceptionTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(WorldNotLoadedException::class, new WorldNotLoadedException('Invalid world exception'));
    }

    public function testReturnCorrectMessage()
    {
        $m1 = 'Invalid world exception';
        $m2 = 'Another world invalid';

        $ex1 = new WorldNotLoadedException($m1);
        $ex2 = new WorldNotLoadedException($m2);

        $this->assertEquals($m1,  $ex1->getMessage());
        $this->assertEquals($m2,  $ex2->getMessage());
    }

    public function testCorrectCode()
    {
        $code2 = 30;

        $ex1 = new WorldNotLoadedException('A message');
        $ex2 = new WorldNotLoadedException('Another message', $code2);


        $this->assertEquals(0,  $ex1->getCode());
        $this->assertEquals($code2,  $ex2->getCode());
    }

    public function testCorrectPrevious()
    {

        $ex1 = new WorldNotLoadedException('BBbbbBBbb');
        $prevException = new Exception('OOpps!');
        $ex3 = new WorldNotLoadedException('AAaaaaAA', null, $prevException);

        $this->assertNull($ex1->getPrevious());
        $this->assertSame($prevException, $ex3->getPrevious()); 
    }


    public function testCanThrow()
    {
        $this->expectException(WorldNotLoadedException::class);
        throw new WorldNotLoadedException('aaaaAAaaaaAA');
    }
}
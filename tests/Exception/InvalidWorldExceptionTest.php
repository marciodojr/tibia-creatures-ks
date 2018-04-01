<?php

namespace Mdojr\Scraper\Exception;

use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\Exception\InvalidWorldException;
use Exception;

class InvalidWorldExceptionTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(InvalidWorldException::class, new InvalidWorldException('Invalid world exception'));
    }

    public function testReturnCorrectMessage()
    {
        $m1 = 'Invalid world exception';
        $m2 = 'Another world invalid';

        $ex1 = new InvalidWorldException($m1);
        $ex2 = new InvalidWorldException($m2);

        $this->assertEquals($m1,  $ex1->getMessage());
        $this->assertEquals($m2,  $ex2->getMessage());
    }

    public function testCorrectCode()
    {
        $code2 = 30;

        $ex1 = new InvalidWorldException('A message');
        $ex2 = new InvalidWorldException('Another message', $code2);


        $this->assertEquals(0,  $ex1->getCode());
        $this->assertEquals($code2,  $ex2->getCode());
    }

    public function testCorrectPrevious()
    {

        $ex1 = new InvalidWorldException('BBbbbBBbb');
        $prevException = new Exception('OOpps!');
        $ex3 = new InvalidWorldException('AAaaaaAA', null, $prevException);

        $this->assertNull($ex1->getPrevious());
        $this->assertSame($prevException, $ex3->getPrevious()); 
    }


    public function testCanThrow()
    {
        $this->expectException(InvalidWorldException::class);
        throw new InvalidWorldException('aaaaAAaaaaAA');
    }
}
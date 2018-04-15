<?php

namespace Mdojr\Scraper;

use Mdojr\Scraper\WorldScraper;
use PHPUnit\Framework\TestCase;
use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\WorldResultArray;
use Mdojr\Scraper\World\World;
use Requests_Session;
use Requests_Response;

class WorldScraperTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $worlds = new WorldArray([]);
        $rs = $this->getRequestSession();
        $this->assertInstanceOf(WorldScraper::class, new WorldScraper($rs, $worlds));

        $otherWorlds = new WorldArray([
            new World(World::FIDERA),
            new World(World::PACERA)
        ]);
        $this->assertInstanceOf(WorldScraper::class, new WorldScraper($rs, $otherWorlds));
    }

    public function testCorrectGetFetchDelayInSeconds()
    {
        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs);
        $this->assertEquals(WorldScraper::DEFAULT_FETCH_DELAY, $ws->getFetchDelayInSeconds());
        $anotherFetchDelay = 10;
        $ws = new WorldScraper($rs, null, $anotherFetchDelay);
        $this->assertEquals($anotherFetchDelay, $ws->getFetchDelayInSeconds());
    }

    public function testCanCreateCorrectInstanceWithAllWorlds()
    {
        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs);
        $this->assertInstanceOf(WorldScraper::class, $ws);
        $this->assertInstanceOf(WorldArray::class, $ws->getWorldList());
    }

    public function testCanFetchWorldResult()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs, $worlds);
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

        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs, $worlds);
        $result1 = $ws->fetch();
        $result2 = $ws->fetch();
        $this->assertInstanceOf(WorldResultArray::class, $result1);
        $this->assertInstanceOf(WorldResultArray::class, $result2);
    }

    public function testCanFetchAll()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs, $worlds);
        $results = $ws->fetchAll();

        $this->assertTrue(is_array($results));
        foreach($results as $r) {
            $this->assertInstanceOf(WorldResultArray::class, $r);
        }
    }

    public function testCanFetchAllAllWorlds()
    {
        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs);
        $res = $ws->fetchAll();

        $this->assertTrue(is_array($res));
    }

    public function testReturnNullAfterLoadAllWorldResults()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs, $worlds);
        $results = $ws->fetch();
        $results = $ws->fetch();
        $this->assertNull($ws->fetch());
    }

    public function testCanResetFetchIndex()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs, $worlds);
        $results = $ws->fetch();
        $results = $ws->fetch();
        $ws->resetCurrentFetchIndex();
        $this->assertInstanceOf(WorldResultArray::class, $ws->fetch());
    }

    public function testMultipleFetchAll()
    {
        $world1 = new World(World::FIDERA);
        $world2 = new World(World::LUMINERA);

        $worlds = new WorldArray([
            $world1,
            $world2
        ]);

        $rs = $this->getRequestSession();
        $ws = new WorldScraper($rs, $worlds);

        $this->assertTrue(is_array($ws->fetchAll()));
        $this->assertTrue(is_array($ws->fetchAll()));
    }

    public function getRequestSession()
    {
        $realRequest = getenv('RREQUEST');
        if (!$realRequest) {
            $fakeRequestSession = $this
            ->getMockBuilder(Requests_Session::class)
            ->setMethods(['post'])
            ->getMock();

            $fakeResponse = $this
            ->getMockBuilder(Requests_Response::class)
            ->getMock();

            $fakeResponse->status_code = 200;
            $fakeResponse->success = true;
            $fakeResponse->body = $this->getHtml();

            $fakeRequestSession
            ->expects($this->any())
            ->method('post')
            ->willReturn($fakeResponse);

            return $fakeRequestSession;
        }

        return new Requests_Session();
    }

    private function getHtml()
    {
        return "<table></table>
            <table></table>
            <table></table>
            <table></table>
            <table>
                <tr>
                    <td>Zombies</td>
                    <td>19</td>
                    <td>1000</td>
                    <td>20</td>
                    <td>2000</td>
                </tr>
                <tr>
                    <td>Tortoise</td>
                    <td>5</td>
                    <td>10</td>
                    <td>25</td>
                    <td>30</td>
                </tr>
            </table>";
    }
}

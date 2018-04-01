<?php

namespace Mdojr\Scraper;

use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\WorldResultArray;
use Mdojr\Scraper\World\WorldResult;
use Mdojr\Scraper\Exception\WorldNotLoadedException;
use Requests;
use DOMDocument;
use DOMElement;
use Requests\Exception as RequestException;

class WorldScraper
{
    const URL = 'https://secure.tibia.com/community/?subtopic=killstatistics';
    const WORLD_ALL = 0;
    private $worldList;
    private $currentFetchIndex;
    private $result;

    public function __construct(WorldArray $chosenWorlds)
    {
        $this->worldList = $chosenWorlds;
        $this->currentFetchIndex = -1;
        $this->result = [];
    }

    public function fetch()
    {
        $this->currentFetchIndex++;
        $world = $this->worldList->offsetGet($this->currentFetchIndex);

        try {
            $response = Requests::post(self::URL, [], $data = [
                'world' => $world->getWorld(),
            ]);

            if ($response->success) {
                $rawTable = strstr(strstr($response->body, '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=3 WIDTH=100%>'), '</TABLE>', true) . "</table>";
                @$table = DOMDocument::loadHTML($rawTable);
                $this->parseTableData($table);
            } else {
                throw new WorldNotLoadedException("Unable to load world information status $response->status_code",  null);
            }
        } catch(RequestException $re) {
            throw new WorldNotLoadedException('Unable to load world information due to request error',  null, $re);
        }
        

        return $this->result[$this->currentFetchIndex];
    }

    public function fetchAll()
    {
        $length = $this->worldList->count();
        for ($i = 0; $i < $length; $i++) {
            $this->fetch();
        }
        return $this->result;
    }

    private function parseTableData(DOMDocument $table)
    {
        $rows = $table->firstChild->nextSibling->firstChild->firstChild;
        $rows->removeChild($rows->firstChild);
        $rows->removeChild($rows->firstChild);
        $rows->removeChild($rows->lastChild);

        $this->result[$this->currentFetchIndex] = new WorldResultArray();
        foreach ($rows->childNodes as $tr) {
            $fc = $tr->firstChild;
            $ns = $fc->nextSibling;
            $res = new WorldResult(trim($fc->textContent, " \t\n\r\0\x0B\xC2\xA0"), (int)$ns->textContent, (int)$ns->nextSibling->textContent);
            $this->result[$this->currentFetchIndex]->append($res);
        }
    }
}

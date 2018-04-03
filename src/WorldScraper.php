<?php

namespace Mdojr\Scraper;

use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\Factory\WorldFactory;
use Mdojr\Scraper\World\WorldResultArray;
use Mdojr\Scraper\World\WorldResult;
use Mdojr\Scraper\Exception\WorldNotLoadedException;
use Requests;
use DOMDocument;
use DOMElement;
use Requests\Exception as RequestException;

/**
 * Permite buscar as informações de morte de jogadores para criaturas e morte de criaturas por jogadores
 */
class WorldScraper
{
    const URL = 'https://secure.tibia.com/community/?subtopic=killstatistics';
    const DEFAULT_FETCH_DELAY = 2;

    private $worldList;
    private $currentFetchIndex;
    private $result;
    private $fetchDelayInSeconds;

    /**
     * Lista de mundos que serão consultados. Se nenhum mundo for informado consulta todos os existentes
     * 
     * @param WorldArray $chosenWorlds Mundos que serão consultados
     * @param int $fetchDelayInSeconds Tempo entre fetch()'s ao usar o método fetchAll().
     */
    public function __construct(WorldArray $chosenWorlds = null, int $fetchDelayInSeconds = self::DEFAULT_FETCH_DELAY)
    {
        if($chosenWorlds === null) {
            $chosenWorlds = WorldFactory::createAll();
        }

        $this->worldList = $chosenWorlds;
        $this->resetCurrentFetchIndex();   
        $this->result = [];
        $this->fetchDelayInSeconds = $fetchDelayInSeconds;
    }

    /**
     * Carrega as informações do mundo $this->worldList->offsetGet($this->currentFetchIndex)
     * 
     * @return WorldResultArray|null Vetor com resultados do mundo ou null caso todos os mundos já tenham sido percorridos.
     */
    public function fetch()
    {
        if(($this->currentFetchIndex + 1) == $this->worldList->count()) {
            return null;
        }
        $this->currentFetchIndex++;
        
        $world = $this->worldList->offsetGet($this->currentFetchIndex);

        try {
            $response = Requests::post(self::URL, [], $data = [
                'world' => (string)$world,
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
        $this->resetCurrentFetchIndex();
        $length = $this->worldList->count();
        for ($i = 0; $i < $length; $i++) {
            $this->fetch();
            sleep($this->fetchDelayInSeconds);
        }
        return $this->result;
    }

    public function resetCurrentFetchIndex()
    {
        $this->currentFetchIndex = -1;
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

    /**
     * Retorna a lista de mundos informada
     * 
     * @return WorldArray Lita de mundos
     */
    public function getWorldList()
    {
        return $this->worldList;
    }

    public function getFetchDelayInSeconds()
    {
        return $this->fetchDelayInSeconds;
    }
}

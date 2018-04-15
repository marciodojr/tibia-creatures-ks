<?php

namespace Mdojr\Scraper;

use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\Factory\WorldFactory;
use Mdojr\Scraper\World\WorldResultArray;
use Mdojr\Scraper\World\WorldResult;
use Mdojr\Scraper\Exception\WorldNotLoadedException;
use Requests_Session;
use DOMDocument;
use DOMXPath;
use DOMNodeList;
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
    private $session;

    /**
     * Lista de mundos que serão consultados. Se nenhum mundo for informado consulta todos os existentes
     * 
     * @param WorldArray $chosenWorlds Mundos que serão consultados
     * @param int $fetchDelayInSeconds Tempo entre fetch()'s ao usar o método fetchAll().
     */
    public function __construct(Requests_Session $rs, WorldArray $chosenWorlds = null, int $fetchDelayInSeconds = self::DEFAULT_FETCH_DELAY)
    {
        if($chosenWorlds === null) {
            $chosenWorlds = WorldFactory::createAll();
        }

        $this->worldList = $chosenWorlds;
        $this->resetCurrentFetchIndex();
        $this->result = [];
        $this->fetchDelayInSeconds = $fetchDelayInSeconds;
        $this->session = $rs;
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
        $dom = new DOMDocument();

        try {
            $response = $this->session->post(self::URL, [], $data = [
                'world' => (string)$world,
            ]);

            if ($response->success) {
                @$dom->loadHTML($response->body);
                $finder = new DOMXPath($dom);
                $nodes = $finder->query('(//table)[5]/tr/td/text()');
                $this->parseTableData($nodes);
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

    private function parseTableData(DOMNodeList $list)
    {
        $this->result[$this->currentFetchIndex] = new WorldResultArray();
        for($i = 0; $i < $list->length; $i += 5) {
            $creatureName = trim($list->item($i)->textContent, " \t\n\r\0\x0B\xC2\xA0");
            $killedPlayers = (int)$list->item($i + 1)->textContent;
            $killedByPlayers = (int)$list->item($i + 2)->textContent;

            $this->result[$this->currentFetchIndex]->append(new WorldResult($creatureName, $killedPlayers, $killedByPlayers));
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

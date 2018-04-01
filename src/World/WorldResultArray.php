<?php

namespace Mdojr\Scraper\World;

use ArrayObject;
use Mdojr\Scraper\World\WorldResult;
use Mdojr\Scraper\Exception\InvalidWorldResultException;

class WorldResultArray extends ArrayObject
{
    public function __construct(array $results = [])
    {
        $invalidIndexes = $this->hasInvalidWorldResult($results);
        if($invalidIndexes !== false) {
            throw new InvalidWorldResultException("Invalid world result at indexes '" . implode(',', $invalidIndexes) . "'");
        }

        parent::__construct($results);
    }

    public function append($result)
    {
        if(!($result instanceof WorldResult)) {
            throw new InvalidWorldResultException("Invalid world result '$result'");
        }

        parent::append($result);
    }

    public function offsetSet($index, $newResult)
    {
        if(!($newResult instanceof WorldResult)) {
            throw new InvalidWorldResultException("Invalid world result '$newResult'");
        }

        parent::offsetSet($index, $newResult);
    }

    public function exchangeArray($results)
    {
        $invalidIndexes = $this->hasInvalidWorldResult($results);
        if($invalidIndexes !== false) {
            throw new InvalidWorldResultException("Invalid world result at indexes '" . implode(',', $invalidIndexes) . "'");
        }

        return parent::exchangeArray($results);
    }

    /**
     * Retorna os índices de resultados de mundos inválidos ou false caso todos os resultados sejam válidos
     * 
     * @param array         $worlds Resultados de Mundos que serão verificados
     * @return array|false  vetor com os índices dos resultados de mundos inválidos ou false caso todos os resultados sejam válidos
     */
    private function hasInvalidWorldResult(array $results)
    {
        $indexes = [];
        foreach($results as $index => $result) {
            if (!($result instanceof WorldResult)) {
                $indexes[] = $index;
            }
        }

        return count($indexes) ? $indexes : false;
    }
}
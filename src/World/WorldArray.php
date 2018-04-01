<?php

namespace Mdojr\Scraper\World;

use ArrayObject;
use Mdojr\Scraper\World\World;
use Mdojr\Scraper\Exception\InvalidWorldException;

class WorldArray extends ArrayObject
{
    public function __construct(array $worlds = [])
    {
        $invalidIndexes = $this->hasInvalidWorld($worlds);
        if($invalidIndexes !== false) {
            throw new InvalidWorldException("Invalid world at indexes '" . implode(',', $invalidIndexes) . "'");
        }

        parent::__construct($worlds);
    }

    public function append($world)
    {
        if(!($world instanceof World)) {
            throw new InvalidWorldException("Invalid world '$world'");
        }

        parent::append($world);
    }

    public function offsetSet($index, $newWorld)
    {
        if(!($newWorld instanceof World)) {
            throw new InvalidWorldException("Invalid world '$newWorld'");
        }

        parent::offsetSet($index, $newWorld);
    }

    public function exchangeArray($worlds)
    {
        $invalidIndexes = $this->hasInvalidWorld($worlds);
        if($invalidIndexes !== false) {
            throw new InvalidWorldException("Invalid world at indexes '" . implode(',', $invalidIndexes) . "'");
        }

        return parent::exchangeArray($worlds);
    }

    /**
     * Retorna os índices de mundos inválidos ou false caso todos os mundos sejam válidos
     * 
     * @param array         $worlds Mundos que serão verificados
     * @return array|false  vetor com os índices dos mundos inválidos ou false caso todos os mundos sejam válidos
     */
    private function hasInvalidWorld(array $worlds)
    {
        $indexes = [];
        foreach($worlds as $index => $world) {
            if (!($world instanceof World)) {
                $indexes[] = $index;
            }
        }

        return count($indexes) ? $indexes : false;
    }
}
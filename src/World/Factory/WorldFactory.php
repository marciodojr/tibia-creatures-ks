<?php

namespace Mdojr\Scraper\World\Factory;

use Mdojr\Scraper\World\World;
use Mdojr\Scraper\World\WorldArray;

class WorldFactory
{

    /**
     * Cria um WorldArray com todos os mundos existentes
     * 
     * @return WorldArray Vetor com todos os mundos existentes
     */
    public static function createAll()
    {
        $possibleWorldsNames = World::getAllWorlds();
        $worlds = [];

        foreach($possibleWorldsNames as $key => $name) {
            $worlds[] = new World($name);
        }

        return new WorldArray($worlds);
    }
}
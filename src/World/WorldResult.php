<?php

namespace Mdojr\Scraper\World;


class WorldResult
{

    /**
     * @var string creature name
     */
    private $creature;

    /**
     * @var int number of times the creature kills players
     */
    private $killedPlayers;

    /**
     * @var int number of times of creature death's by players
     */
    private $killedByPlayers;

    public function  __construct(string $creature, int $killedPlayers, int $killedByPlayers)
    {
        $this->creature = $creature;
        $this->killedPlayers = $killedPlayers;
        $this->killedByPlayers = $killedByPlayers;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
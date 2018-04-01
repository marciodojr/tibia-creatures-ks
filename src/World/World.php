<?php

namespace Mdojr\Scraper\World;

use Mdojr\Scraper\Exception\InvalidWorldException;
use ReflectionClass;

/**
 * Define um dos mundos de Tibia.
 * Ex: new World(World::GARNERA)
 */
class World
{

    const AMERA = 'Amera';
    const ANTICA = 'Antica';
    const ASTERA = 'Astera';
    const BELOBRA = 'Belobra';
    const BENEVA = 'Beneva';
    const CALMERA = 'Calmera';
    const CANDIA = 'Candia';
    const CELESTA = 'Celesta';
    const CHRONA = 'Chrona';
    const DAMORA = 'Damora';
    const DESCUBRA = 'Descubra';
    const DUNA = 'Duna';
    const ELDERA = 'Eldera';
    const ESTELA = 'Estela';
    const FEROBRA = 'Ferobra';
    const FIDERA = 'Fidera';
    const FORTERA = 'Fortera';
    const GARNERA = 'Garnera';
    const GENTEBRA = 'Gentebra';
    const GUARDIA = 'Guardia';
    const HARMONIA = 'Harmonia';
    const HELERA = 'Helera';
    const HONBRA = 'Honbra';
    const HONERA = 'Honera';
    const IMPERA = 'Impera';
    const INABRA = 'Inabra';
    const JULERA = 'Julera';
    const JUSTERA = 'Justera';
    const KALIBRA = 'Kalibra';
    const KENORA = 'Kenora';
    const LAUDERA = 'Laudera';
    const LUMINERA = 'Luminera';
    const LUTABRA = 'Lutabra';
    const MACABRA = 'Macabra';
    const MAGERA = 'Magera';
    const MENERA = 'Menera';
    const MORTA = 'Morta';
    const MORTERA = 'Mortera';
    const NERANA = 'Nerana';
    const NOCTERA = 'Noctera';
    const OLERA = 'Olera';
    const OLYMPA = 'Olympa';
    const PACERA = 'Pacera';
    const PELORIA = 'Peloria';
    const PREMIA = 'Premia';
    const QUELIBRA = 'Quelibra';
    const QUINTERA = 'Quintera';
    const REFUGIA = 'Refugia';
    const RELEMBRA = 'Relembra';
    const SERDEBRA = 'Serdebra';
    const SILVERA = 'Silvera';
    const SOLERA = 'Solera';
    const TAVARA = 'Tavara';
    const THERA = 'Thera';
    const TORTURA = 'Tortura';
    const UMERA = 'Umera';
    const UNITERA = 'Unitera';
    const VELUDERA = 'Veludera';
    const VITA = 'Vita';
    const VUNIRA = 'Vunira';
    const XANTERA = 'Xantera';
    const ZANERA = 'Zanera';
    const ZUNA = 'Zuna';
    const ZUNERA = 'Zunera';

    /**
     * @var string  Mundo escolhido
     */
    private $world;

    /**
     * Cria um objeto World a partir de uma das constantes de mundo. 
     * Ex: new World(World::THERA)
     * 
     * @param string $world Um dos mundos definido pelas constantes dessa classe.
     */
    public function __construct(string $world)
    {
        if(!$this->isWorldValid($world)) {
            throw new InvalidWorldException("World '$world' is invalid");
        }

        $this->world = $world;
    }

    /**
     * Verifica se um mundo $world é válido ou não
     * 
     * @param $world Mundo a ser testado
     * 
     * @return bool true se válido false caso contrário
     */
    private function isWorldValid($world)
    {
        $validWorlds = self::getAllWorlds();
        return in_array($world, $validWorlds);
    }

    /**
     * Retorna os mundos existentes.
     * 
     * @return  array Mundos existentes no formato [WORLD1 => World1, WORLD2 => World2, ...]
     */
    public static function getAllWorlds()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    /**
     * Retorna o mundo definido no construtor
     * @return string Mundo
     */
    public function __get($name)
    {
        return $this->$name;
    }

    public function __toString()
    {
        return $this->world;
    }
}
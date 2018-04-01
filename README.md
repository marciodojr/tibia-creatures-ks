# Tibia Creatures Kill Statistics

Load creature deaths and kills from tibia.com ([killstatistics](https://secure.tibia.com/community/?subtopic=killstatistics)).

## How to install

```
composer require marciodojr/tibia-creatures-ks
```

## How to use

```php

use Mdojr\Scraper\WorldScraper;
use Mdojr\Scraper\World\WorldArray;
use Mdojr\Scraper\World\WorldResultArray;
use Mdojr\Scraper\World\World;

$world1 = new World(World::FIDERA);
$world2 = new World(World::LUMINERA);

$worlds = new WorldArray([
    $world1,
    $world2
]);

$ws = new WorldScraper($worlds);

// to load all worlds info leave the constructor empty
//$ws = new WorldScrapper();

// fetch one by one
//$resultWorld1 = $ws->fetch(); 
//$resultWorld2 = $ws->fetch();
// or simply 
$results = $ws->fetchAll();
var_dump($results);
/*
  [0]=>
  object(Mdojr\Scraper\World\WorldResultArray)#1771 (1) {
    ["storage":"ArrayObject":private]=>
    array(843) {
      [0]=>
      object(Mdojr\Scraper\World\WorldResult)#1760 (3) {
        ["creature":"Mdojr\Scraper\World\WorldResult":private]=>
        string(18) "(elemental forces)"
        ["killedPlayers":"Mdojr\Scraper\World\WorldResult":private]=>
        int(4)
        ["killedByPlayers":"Mdojr\Scraper\World\WorldResult":private]=>
        int(0)
      }
      [1]=>
      object(Mdojr\Scraper\World\WorldResult)#1761 (3) {
        ["creature":"Mdojr\Scraper\World\WorldResult":private]=>
        string(9) "Abyssador"
        ["killedPlayers":"Mdojr\Scraper\World\WorldResult":private]=>
        int(0)
        ["killedByPlayers":"Mdojr\Scraper\World\WorldResult":private]=>
        int(1)
      }
      [2]=>
      object(Mdojr\Scraper\World\WorldResult)#1762 (3) {
        ["creature":"Mdojr\Scraper\World\WorldResult":private]=>
        string(5) "Achad"
        ["killedPlayers":"Mdojr\Scraper\World\WorldResult":private]=>
        int(0)
        ["killedByPlayers":"Mdojr\Scraper\World\WorldResult":private]=>
        int(1)
      }
    ...
*/
```

## How to test

```
composer test
```

## License 
MIT
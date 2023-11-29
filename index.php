<?php

/*
 * This file is part of the OpenClassRoom PHP Object Course.
 *
 * (c) Grégoire Hébert <contact@gheb.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

class Lobby
{
    /** @var array<QueuingPlayer> */
    public $queuingPlayers = [];

    public function findOponents(QueuingPlayer $player)
    {
        $minLevel = round($player->getRatio() / 100);
        $maxLevel = $minLevel + $player->getRange();

        return array_filter($this->queuingPlayers, static function (QueuingPlayer $potentialOponent) use ($minLevel, $maxLevel, $player) {
            $playerLevel = round($potentialOponent->getRatio() / 100);

            return $player !== $potentialOponent && ($minLevel <= $playerLevel) && ($playerLevel <= $maxLevel);
        });
    }

    public function addPlayer(SimplePlayer $player)
    {
        $this->queuingPlayers[] = new QueuingPlayer($player);
    }

    public function addPlayers(SimplePlayer ...$players)
    {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }
    }
}

abstract class Player
{

    //protected $player;
    protected $name;
    protected $ratio;

    abstract public function __construct($name, $ratio = 400.0);
    /*{
        $this->name = $name;
        $this->ratio = $ratio;
    }*/

    abstract public function getName();
    /*{
        return $this->name;
    }*/

    abstract public function probabilityAgainst($player);
    /*{
        return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
    }*/

    abstract public function updateRatioAgainst($player, int $result);
    /*{
        $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
    }*/

    abstract public function getRatio();
    /*{
        return $this->ratio;
    }*/
}

class SimplePlayer extends Player
{

    public function __construct($name, $ratio = 400.0)
    {
        $this->name = $name;
        $this->ratio = $ratio;
    }

    public function getName()
    {
        return $this->name;
    }

    public function probabilityAgainst($player)
    {
        return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
    }

    public function updateRatioAgainst($player, int $result)
    {
        $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
    }

    public function getRatio()
    {
        return $this->ratio;
    }
}


final class QueuingPlayer extends SimplePlayer
{
    protected $range;

    public function __construct(SimplePlayer $player, $range = 1){
        $this->name = $player->getName();
        $this->ratio = $player->getRatio();
        $this->range = $range;
    }

    public function getRange(){
        return $this->range;
    }

}


$greg = new SimplePlayer('greg', 400);
$jade = new SimplePlayer('jade', 476);

$lobby = new Lobby();
$lobby->addPlayers($greg, $jade);

var_dump($lobby->findOponents($lobby->queuingPlayers[0]));

exit(0);

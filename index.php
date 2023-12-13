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
namespace App\MatchMaker\Waiting {
    use App\MatchMaker\Players\QueuingPlayer;
    use App\MatchMaker\Players\Player;
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

        public function addPlayer(Player $player)
        {
            $this->queuingPlayers[] = new QueuingPlayer($player);
        }

        public function addPlayers(Player ...$players)
        {
            foreach ($players as $player) {
                $this->addPlayer($player);
            }
        }
    }
}
namespace App\MatchMaker\Players {
    abstract class AbstractPlayer
    {   
        public $name;
        public $ratio;
        public function __construct($name = 'anonymous', $ratio = 400.0)
        {
            $this->name = $name;
            $this->ratio = $ratio;
        }

        abstract public function getName();

        abstract public function getRatio();

        abstract protected function probabilityAgainst(self $player);

        abstract public function updateRatioAgainst(self $player, $result);
    }

    class Player extends AbstractPlayer
    {
        public function getName()
        {
            return $this->name;
        }

        protected function probabilityAgainst(AbstractPlayer $player)
        {
            return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
        }

        public function updateRatioAgainst(AbstractPlayer $player, $result)
        {
            $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
        }

        public function getRatio()
        {
            return $this->ratio;
        }
    }

    class QueuingPlayer extends Player
    {
        protected $range;
        public function __construct(AbstractPlayer $player, $range = 1)
        {
            parent::__construct($player->getName(), $player->getRatio());
            $this->range = $range;
        }

        public function getRange()
        {
            return $this->range;
        }

        public function upgradeRange()
        {
            $this->range = min($this->range + 1, 40);
        }
    }

    class BlitzPlayer extends Player
    {
        
        public function __construct($name = 'anonymous', $ratio = 1200.0)
        {
            parent::__construct($name, $ratio);
        }

        public function updateRatioAgainst(AbstractPlayer $player, $result)
        {
            $this->ratio += 128 * ($result - $this->probabilityAgainst($player));
        }
    }
}
namespace {
use App\MatchMaker\Players\BlitzPlayer;
use App\MatchMaker\Waiting\Lobby;

$greg = new BlitzPlayer('greg');
$jade = new BlitzPlayer('jade');

$lobby = new Lobby();
$lobby->addPlayers($greg, $jade);

var_dump($lobby->findOponents($lobby->queuingPlayers[0]));

exit(0);
}

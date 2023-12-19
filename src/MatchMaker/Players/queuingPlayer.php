<?php

declare(strict_types=1);
namespace App\MatchMaker\Players {
    use App\MatchMaker\Players\AbstractPlayer;
    use App\MatchMaker\Players\Player;
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
}
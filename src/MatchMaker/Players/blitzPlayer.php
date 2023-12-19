<?php

declare(strict_types=1);
namespace App\MatchMaker\Players {
    use App\MatchMaker\Players\AbstractPlayer;
    use App\MatchMaker\Players\Player;
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
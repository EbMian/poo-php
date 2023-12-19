<?php

declare(strict_types=1);
namespace App\MatchMaker\Players {
    use App\MatchMaker\Players\AbstractPlayer;
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
}
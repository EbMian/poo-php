<?php

declare(strict_types=1);
namespace App\MatchMaker\Waiting {
    use App\MatchMaker\Players\QueuingPlayer;
    use App\MatchMaker\Players\Player;
    
    interface LobbyInterface
    {
        public function findOponents(QueuingPlayer $player);
        public function addPlayer(Player $player);
        public function addPlayers(Player ...$players);
    
    }
    
    class Lobby implements LobbyInterface
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
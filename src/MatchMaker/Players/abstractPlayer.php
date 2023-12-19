<?php

declare(strict_types=1);

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
}
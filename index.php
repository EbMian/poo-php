<?php
declare(strict_types=1);
class Player {
    private $level;

    public function __construct() {
        $this->level = 0;
    }

    public function getLevel() {
        return $this->level;
    }

    public function setLevel($level) {
        $this->level = $level;
        return $this;
    }

}

class Encounter {
    const RESULT_WINNER = 1;
    const RESULT_LOSER = -1;
    const RESULT_DRAW = 0;
    const RESULT_POSSIBILITIES = [self::RESULT_WINNER, self::RESULT_LOSER, self::RESULT_DRAW];
    public static function probabilityAgainst(int $levelPlayerOne, int $againstLevelPlayerTwo)
    {
        return 1/(1+(10 ** (($againstLevelPlayerTwo - $levelPlayerOne)/400)));
    }

    public static function setNewLevel(int &$levelPlayerOne, int $againstLevelPlayerTwo, int $playerOneResult)
    {
        if (!in_array($playerOneResult, self::RESULT_POSSIBILITIES)) {
            trigger_error(sprintf('Invalid result. Expected %s',implode(' or ', self::RESULT_POSSIBILITIES)));
        }

        $levelPlayerOne += (int) (32 * ($playerOneResult - self::probabilityAgainst($levelPlayerOne, $againstLevelPlayerTwo)));
    }
    
}




$greg = new Player();
$greg->setLevel(400);
$gregLevel = $greg->getLevel();
$jade = new Player();
$jade->setLevel(800);
$jadeLevel = $jade->getLevel();

$jadeVsGreg = new Encounter();

echo sprintf(
    'Greg à %.2f%% chance de gagner face a Jade',
    $jadeVsGreg->probabilityAgainst($greg->getLevel(), $jade->getLevel())*100
).PHP_EOL;

// Imaginons que greg l'emporte tout de même.
$jadeVsGreg->setNewLevel($gregLevel, $jadeLevel, $jadeVsGreg::RESULT_WINNER);
$jadeVsGreg->setNewLevel($gregLevel, $jadeLevel, $jadeVsGreg::RESULT_LOSER);

echo sprintf(
    'les niveaux des joueurs ont évolués vers %s pour Greg et %s pour Jade',
    $greg->getLevel(),
    $jade->getLevel()
);

exit(0);

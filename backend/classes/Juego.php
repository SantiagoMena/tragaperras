<?php
namespace Tragaperras;

class Juego
{
    public $apuesta;
    public $lineas;
    public $totalApuesta;
    public $esBonus;

    public function __construct(float $apuesta, int $lineas, bool $esBonus = FALSE)
    {
        $this->apuesta = $apuesta;
        $this->lineas = $lineas;
        $this->totalApuesta = $apuesta * $lineas;
        $this->esBonus = $esBonus;
    }
}

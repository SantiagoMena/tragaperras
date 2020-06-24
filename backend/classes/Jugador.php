<?php
namespace Tragaperras;

class Jugador
{
    public $totalApuestas;
    public $totalGanancias;
    public $totalPerdidas;
    
    public function __construct(float $totalApuestas, float $totalGanancias)
    {
        $this->totalApuestas = $totalApuestas;
        $this->totalGanancias = $totalGanancias;
        $this->totalPerdidas = $totalApuestas - $totalGanancias;
    }
}
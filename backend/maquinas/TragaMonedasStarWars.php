<?php
namespace Tragaperras\Maquinas;
use Tragaperras\Elemento;
use Tragaperras\Linea;
use Tragaperras\Jackpot;
use Tragaperras\Jugador;
use Tragaperras\Maquina;

class TragaMonedasStarWars
{
    public $maquina;

    public function __construct(int $numeroLineas, array $jackpots, Jugador $jugador)
    {
        $elementos = [
            new Elemento('at_at', [3 => 60, 4 => 80, 5 => 150], 'at_at'),
            new Elemento('darth_vader', [3 => 50, 4 => 70, 5 => 140], 'darth_vader'),
            new Elemento('c3po', [3 => 40, 4 => 60, 5 => 80], 'c3po'),
            new Elemento('falcon', [3 => 30, 4 => 50, 5 => 70], 'falcon'),
            new Elemento('r2d2', [3 => 15, 4 => 30, 5 => 50], 'r2d2'),
            new Elemento('stormtrooper', [3 => 10, 4 => 15, 5 => 30], 'stormtrooper'),
            new Elemento('tie_ln', [3 => 10, 4 => 15, 5 => 20], 'tie_ln'),


            new Elemento('comodin', [3 => 250, 4 => 500, 5 => 2000], 'yoda'),
            new Elemento('bonus', [3 => 0, 4 => 0, 5 => 0], 'death_star'),
        ];

        $lineas = Linea::getLineas($numeroLineas);

        $this->maquina = new Maquina($jugador, 100, $lineas, $elementos, $jackpots);
    }

    public function partida(float $apuesta): array
    {
        return $this->maquina->partida($apuesta);
    }

    public static function probar(int $numeroLineas, float $apuesta, float $saldoInicialJackpot, float $saldoLimiteJackPot, int $elementosJackpot, float $apuestasJugador, float $gananciasJugador): array
    {

        $jackpots = [
            new Jackpot($saldoInicialJackpot, $saldoLimiteJackPot, $elementosJackpot)
        ];
        
        $jugador = new Jugador($apuestasJugador, $gananciasJugador);

        $maquina = new self($numeroLineas, $jackpots, $jugador);

        return $maquina->partida($apuesta);
    }
}
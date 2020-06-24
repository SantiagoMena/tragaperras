<?php
namespace Tragaperras;

class Jackpot
{
    private const FILAS = 3;
    private const COLUMNAS = 5;

    public $total;
    public $saldoInicial;
    public $limite;
    public $elementos;
    
    public function __construct(float $saldoInicial, float $limite, int $elementos)
    {
        $this->total = $saldoInicial;
        $this->saldoInicial = $saldoInicial;
        $this->limite = $limite;
        $this->elementos = $elementos;
    }

    public function cargar(float $saldo): void
    {
        $this->total += $saldo;
    }

    public function pagar(): float
    {
        if( $this->total >= $this->limite ){
            $total = $this->total;
            $this->total = $this->saldoInicial;

            return $total;
        }

        return 0;
    }

    public function generarTablero(array $elementos): Tablero
    {
        foreach ( $elementos as $key => $elemento ) {
            if( $elemento->id === 'bonus' ){
                unset($elementos[$key]);
            }

            if( $elemento->id === 'comodin' ){
                unset($elementos[$key]);
            }
        }

        $tablero = Tablero::generarTableroAleatorio($elementos, self::FILAS, self::COLUMNAS);

        $elementoJackpot = new Elemento('jackpot', [], 'jackpot');

        while ($this->elementos > 0) {
            $randomFila = random_int( 0, self::FILAS - 1 );
            $randomColumna = random_int( 0, self::COLUMNAS - 1 );

            if( $tablero->elementos[ $randomFila ][ $randomColumna ]->id === 'jackpot' ){
                continue;
            } else {
                $tablero->elementos[ $randomFila ][ $randomColumna ] = $elementoJackpot;
                $this->elementos--;
            }
        }

        return $tablero;
    }


}

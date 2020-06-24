<?php
namespace Tragaperras;

class Tablero
{
    public $elementos;

    public function __construct(array $elementos)
    {
        $this->elementos = $elementos;
    }

    public function imprimir(): void
    {
        foreach ($this->elementos as $cuadrante) {
            //echo "[";
            foreach ($cuadrante as $elemento) {
                //echo "\t $elemento->id \t";
            }
            //echo "] \n";
        }
        //echo "============================================================\n";
    }

    public function elementoEnJuego(): array
    {
        $elementos = [];
        foreach ($this->elementos as $cuadrante) {
            foreach ($cuadrante as $elemento) {
                if( !in_array($elemento, $elementos, TRUE) ){
                    $elementos[] = $elemento;
                }
            }
        }

        return $elementos;
    }

    public function obtenerBonus(): int
    {
        $total = 0;

        foreach ($this->elementos as $cuadrante) {
            foreach ($cuadrante as $elemento) {
                if($elemento->id === 'bonus'){
                    $total++;
                }
            }
        }

        if($total === 3) {
            return 4;
        } elseif($total === 4) {
            return 8;
        } elseif($total > 4) {
            return 16;
        }

        return 0;
    }


    public static function generarTableroAleatorio(array $elementos, int $filas, int $columnas): Tablero
    {
        for ($i=0; $i < $filas; $i++) { 
            for ($j=0; $j < $columnas; $j++) { 
                $tablero[$i][$j] = $elementos[ random_int( 0, count( $elementos ) - 1 ) ];
            }
        }

        return new Tablero($tablero);
    }
}

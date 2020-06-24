<?php
namespace Tragaperras;

class Linea
{
    public $numero;
    public $tablero;
    public $apariciones;

    public function __construct(array $tablero, int $numero, int $apariciones)
    {
        $this->tablero = $tablero;
        $this->numero = $numero;
        $this->apariciones = $apariciones;
    }

    /**
     * Verificar coincidencias de elementos en base al tablero de la linea
     */
    public function verificarCoincidencias(Elemento $elemento, Tablero $tablero): Resultado
    {
        $coincide = true;
        foreach($this->tablero as $i => $cuadrante){
            foreach($cuadrante as $j => $hayElemento){
                if($hayElemento === 1){
                    // Cheque de coincidencia con elemento o comodin y no es un bonus
                    $coincide = $coincide && ($tablero->elementos[$i][$j] === $elemento || $tablero->elementos[$i][$j]->id === 'comodin' && $tablero->elementos[$i][$j]->id !== 'bonus' );
                }
            }
        }

        return new Resultado(
            $coincide ? $elemento->valores[$this->apariciones] : 0,
            $elemento,
            $this
        );
    }

    public static function getLineas(int $numeroLineas): array
    {
        $numeroLineas = $numeroLineas <= 0 ? 1 : $numeroLineas;

        $totalLineas = [
            [
                // Linea #1
                new Linea(
                    [
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 0, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    1, //Numero
                    3 //Apariciones
                ),
                new Linea(
                    [
                        [0, 0, 0, 0, 0],
                        [0, 0, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    1, //Numero
                    3 //Apariciones
                ),
                new Linea(
                    [
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 1, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    1, //Numero
                    4 //Apariciones
                ),
                new Linea(
                    [
                        [0, 0, 0, 0, 0],
                        [0, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    1, //Numero
                    4 //Apariciones
                ),
                new Linea(
                    [
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    1, //Numero
                    5 //Apariciones
                ),
            ],
            [
                // Lineas #2
                new Linea(
                    [
                        [1, 1, 1, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    2, //Numero
                    3 //Apariciones
                ),
                new Linea(
                    [
                        [0, 0, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    2, //Numero
                    3 //Apariciones
                ),
                new Linea(
                    [
                        [1, 1, 1, 1, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    2, //Numero
                    4 //Apariciones
                ),
                new Linea(
                    [
                        [0, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    2, //Numero
                    4 //Apariciones
                ),
                new Linea(
                    [
                        [1, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                    ], //Tablero
                    2, //Numero
                    5 //Apariciones
                ),
            ]
        ];

        $lineas = [];
        
        for ($i=0; $i < $numeroLineas; $i++) { 
            if( isset($totalLineas[$i]) ){
                foreach ($totalLineas[$i] as $linea) {
                    array_push( $lineas, $linea );
                }
            }
        }

        return $lineas;
    }
}

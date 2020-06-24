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

    private static function linea1(): array
    {
        return [
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
        ];
    }

    private static function linea2(): array
    {
        return [
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
        ];
    }

    private static function linea3(): array
    {
        return [
            // Lineas #3
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                    [1, 1, 1, 0, 0],
                ], //Tablero
                3, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 1, 1],
                ], //Tablero
                3, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                    [1, 1, 1, 1, 0],
                ], //Tablero
                3, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 1],
                ], //Tablero
                3, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                    [1, 1, 1, 1, 1],
                ], //Tablero
                3, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea4(): array
    {
        return [
            // Lineas #4
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                4, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 1, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                4, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 1, 0],
                    [1, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                4, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 1, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                4, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 1, 0],
                    [1, 0, 1, 0, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                4, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea5(): array
    {
        return [
            // Lineas #5
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                    [0, 1, 0, 0, 0],
                ], //Tablero
                5, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 0, 1, 0],
                ], //Tablero
                5, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                    [0, 1, 0, 1, 0],
                ], //Tablero
                5, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 1, 0, 1, 0],
                ], //Tablero
                5, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 1, 0, 1],
                    [0, 1, 0, 1, 0],
                ], //Tablero
                5, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea6(): array
    {
        return [
            // Lineas #6
            new Linea(
                [
                    [1, 1, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                6, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 1, 1],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                6, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [1, 1, 0, 1, 0],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                6, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 1, 1],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                6, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [1, 1, 0, 1, 1],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                6, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea7(): array
    {
        return [
            // Lineas #7
            new Linea(
                [
                    [1, 0, 1, 0, 0],
                    [0, 1, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                7, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 1],
                    [0, 0, 0, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                7, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [1, 0, 1, 0, 0],
                    [0, 1, 0, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                7, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 1],
                    [0, 1, 0, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                7, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [1, 0, 1, 0, 1],
                    [0, 1, 0, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                7, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea8(): array
    {
        return [
            // Lineas #8
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [1, 1, 0, 0, 0],
                ], //Tablero
                8, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 1, 1],
                ], //Tablero
                8, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [1, 1, 0, 1, 0],
                ], //Tablero
                8, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [0, 1, 0, 1, 1],
                ], //Tablero
                8, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [1, 1, 0, 1, 1],
                ], //Tablero
                8, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea9(): array
    {
        return [
            // Lineas #9
            new Linea(
                [
                    [0, 1, 1, 0, 0],
                    [1, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                9, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 1, 0],
                    [0, 0, 0, 0, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                9, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 1, 1, 0],
                    [1, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                9, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 1, 1, 0],
                    [0, 0, 0, 0, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                9, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 1, 1, 0],
                    [1, 0, 0, 0, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                9, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea10(): array
    {
        return [
            // Lineas #10
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                ], //Tablero
                10, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 1, 0],
                    [0, 0, 1, 0, 1],
                ], //Tablero
                10, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 0, 1, 0],
                    [1, 0, 1, 0, 0],
                ], //Tablero
                10, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 0, 1, 0],
                    [0, 0, 1, 0, 1],
                ], //Tablero
                10, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 0, 1, 0],
                    [1, 0, 1, 0, 1],
                ], //Tablero
                10, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea11(): array
    {
        return [
            // Lineas #11
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 1, 0, 0, 0],
                    [1, 0, 0, 0, 0],
                ], //Tablero
                11, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 1, 0],
                    [0, 0, 0, 0, 1],
                ], //Tablero
                11, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 1, 0, 1, 0],
                    [1, 0, 0, 0, 0],
                ], //Tablero
                11, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 1, 0, 1, 0],
                    [0, 0, 0, 0, 1],
                ], //Tablero
                11, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 1, 0, 1, 0],
                    [1, 0, 0, 0, 1],
                ], //Tablero
                11, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea12(): array
    {
        return [
            // Lineas #12
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [1, 1, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                12, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 1, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                12, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [1, 1, 0, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                12, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [0, 1, 0, 1, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                12, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 1, 0, 0],
                    [1, 1, 0, 1, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                12, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea13(): array
    {
        return [
            // Lineas #13
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 1, 0, 0],
                    [1, 0, 0, 0, 0],
                ], //Tablero
                13, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 1, 0],
                    [0, 0, 0, 0, 1],
                ], //Tablero
                13, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                    [1, 0, 0, 0, 0],
                ], //Tablero
                13, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                    [0, 0, 0, 0, 1],
                ], //Tablero
                13, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                    [1, 0, 0, 0, 1],
                ], //Tablero
                13, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea14(): array
    {
        return [
            // Lineas #14
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 1, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                14, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 1, 1],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                14, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 1, 0, 1, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                14, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 1, 0, 1, 1],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                14, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 1, 0, 1, 1],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                14, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea15(): array
    {
        return [
            // Lineas #15
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 0, 0, 0],
                    [0, 1, 1, 0, 0],
                ], //Tablero
                15, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 1],
                    [0, 0, 1, 1, 0],
                ], //Tablero
                15, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                ], //Tablero
                15, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 1],
                    [0, 1, 1, 1, 0],
                ], //Tablero
                15, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 0, 0, 1],
                    [0, 1, 1, 1, 0],
                ], //Tablero
                15, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea16(): array
    {
        return [
            // Lineas #16
            new Linea(
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                16, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 1],
                    [0, 0, 0, 1, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                16, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 0, 1, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                16, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 1],
                    [0, 1, 0, 1, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                16, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [1, 0, 0, 0, 1],
                    [0, 1, 0, 1, 0],
                    [0, 0, 1, 0, 0],
                ], //Tablero
                16, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea17(): array
    {
        return [
            // Lineas #17
            new Linea(
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                17, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 1],
                    [0, 0, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                17, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                17, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 1],
                    [0, 1, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                17, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [1, 0, 0, 0, 1],
                    [0, 1, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                17, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea18(): array
    {
        return [
            // Lineas #18
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                    [0, 1, 0, 0, 0],
                ], //Tablero
                18, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 1, 0],
                    [0, 0, 0, 0, 1],
                ], //Tablero
                18, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 1, 1, 0],
                    [0, 1, 0, 0, 0],
                ], //Tablero
                18, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 1, 0],
                    [0, 1, 0, 0, 1],
                ], //Tablero
                18, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [1, 0, 1, 1, 0],
                    [0, 1, 0, 0, 1],
                ], //Tablero
                18, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea19(): array
    {
        return [
            // Linea #19
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                19, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 0],
                    [0, 0, 1, 1, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                19, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                19, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [0, 0, 1, 1, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                19, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 1, 1],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                19, //Numero
                5 //Apariciones
            ),
        ];
    }

    private static function linea20(): array
    {
        return [
            // Lineas #20
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                20, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 0, 0, 0, 1],
                    [0, 0, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                20, //Numero
                3 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 0, 0],
                    [1, 0, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                20, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 0, 1],
                    [0, 0, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                20, //Numero
                4 //Apariciones
            ),
            new Linea(
                [
                    [0, 1, 0, 0, 1],
                    [1, 0, 1, 1, 0],
                    [0, 0, 0, 0, 0],
                ], //Tablero
                20, //Numero
                5 //Apariciones
            ),
        ];
    }

    public static function getLineas(int $numeroLineas): array
    {
        $numeroLineas = $numeroLineas <= 0 ? 1 : $numeroLineas;

        $totalLineas = [
            self::linea1(),
            self::linea2(),
            self::linea3(),
            self::linea4(),
            self::linea5(),
            self::linea6(),
            self::linea7(),
            self::linea8(),
            self::linea9(),
            self::linea10(),
            self::linea11(),
            self::linea12(),
            self::linea13(),
            self::linea14(),
            self::linea15(),
            self::linea16(),
            self::linea17(),
            self::linea18(),
            self::linea19(),
            self::linea20(),
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

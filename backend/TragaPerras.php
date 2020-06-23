<?php
namespace Tragaperras;

class Maquina
{
    private const FILAS = 3;
    private const COLUMNAS = 5;
    public $jugador;
    public $lineas;
    public $elementos;
    public $jackpots;
    public $porcentajePago;

    public function __construct(Jugador $jugador, float $porcentajePago, array $lineas, array $elementos, array $jackpots)
    {
        $this->jugador = $jugador;
        $this->lineas = $lineas;
        $this->elementos = $elementos;
        $this->jackpots = $jackpots;
        $this->porcentajePago = $porcentajePago;
    }


    public function partida($apuesta): array
    {
        // Llenar jackpots

        // Pagar jackpots
        foreach ($this->jackpots as $jackpot) {
            $ganancia = $jackpot->pagar();
            if( $ganancia > 0 ) {
                //echo "Jackpot: $ganancia\n";
                //echo "============================================================\n";
                return [
                    'resultados' => [
                        'resultados' => [
                            'tablero' => $jackpot->generarTablero($this->elementos),
                            'ganancias' => []
                        ],
                    ],
                    'jackpot' => true,
                    'gananciaTotal' => $ganancia
                ];
            }
        }

        
        // Verificar si se puede pagar la apuesta, sino volver a jugar
        $gananciaTotal = 0;
        $ressultados = [];
        $pagoMaximo = $this->jugador->totalPerdidas * $this->porcentajePago / 100;

        while ( empty($resultados) || $gananciaTotal > $pagoMaximo ) {
            if( !empty($resultados) && $gananciaTotal > 0 ) {
                //echo "Ganancia no pagada: $gananciaTotal\n";
                //echo "============================================================\n";
            }
            
            $resultados = $this->jugar( new Juego($apuesta, count($this->lineas)) );
            $gananciaTotal = Resultado::obtenerGananciaTotal($resultados);
        }

        //echo "Ganancia Total: $gananciaTotal\n";
        //echo "============================================================\n";
        
        return [
            'resultados' => $resultados,
            'gananciaTotal' => $gananciaTotal,
        ];
    }
    
    public function jugar(Juego $juego): array 
    {   
        if( $juego->esBonus ){
            foreach ( $this->elementos as $key => $elemento ) {
                if( $elemento->id === 'bonus' ){
                    unset($this->elementos[$key]);
                }
            }
        }
        
        $tablero = Tablero::generarTableroAleatorio($this->elementos, self::FILAS, self::COLUMNAS);
        $tablero->imprimir();

        $resultadosConsolidados = $this->coincidenciasConsolidadas($tablero);
        $resultados['resultados'] = $resultadosConsolidados;

        // Si hay bonus en el tablero
        if($resultadosConsolidados['bonus'] > 0) {
            //echo "Bonus: " . $resultadosConsolidados['bonus'] . "\n";
            //echo "============================================================\n";
            for ($i=0; $i < $resultadosConsolidados['bonus']; $i++) { 
                $juego->esBonus = TRUE;
                $resultados['juegosExtra'][] = $this->jugar($juego);
            }
        }

        return $resultados;
    }

    /**
     * Obtener coincidencias consolidadas, traer solo mayores a 0 y la más alta por linea
     */
    private function coincidenciasConsolidadas(Tablero $tablero): array
    {
        // Obtengo los resultados de las coincidencias
        $resultados = $this->obtenerCoincidencias($tablero);
        $resultadosConsolidados['ganancias'] = [];
        $resultadosConsolidados['tablero'] = $tablero;
        $resultadosConsolidados['bonus'] = $tablero->obtenerBonus();

        // Recorro los resultados
        foreach ($resultados as $resultado) {
            $numeroLinea = $resultado->linea->numero;

            // Verifico si hay ganancia
            if($resultado->ganancia > 0){
                // Si el resultado de la linea ya fue asignado lo comparo
                if( isset( $resultadosConsolidados['ganancias'][$numeroLinea] ) ){ 
                    // Si el resultado recorrido es mayor al anterior lo guardo
                    if( $resultadosConsolidados['ganancias'][ $numeroLinea ]->ganancia < $resultado->ganancia ){
                        $resultadosConsolidados['ganancias'][ $numeroLinea ] = $resultado;
                    }
                } else {
                    // Si no está asignado lo asigno
                    $resultadosConsolidados['ganancias'][ $numeroLinea ] = $resultado;
                }
            }
        }

        foreach ($resultadosConsolidados['ganancias'] as $resultado) {
            $resultado->imprimir();
        }

        
        return $resultadosConsolidados;
    }

    /**
     * Obtener las coincidencias en base a las lineas de la maquina
     */
    private function obtenerCoincidencias(Tablero $tablero): array 
    {
        $resultados = [];
        // Recorro elementos del juego
        foreach ($tablero->elementoEnJuego() as $elemento) {
            foreach ($this->lineas as $linea) {
                // Alamaceno los resultados de las coincidencias por elemento
                $resultados[] = $linea->verificarCoincidencias($elemento, $tablero);
            }
        }

        return $resultados;
    }

}


class Elemento
{
    public $id;
    public $valores;
    public $imagen;

    public function __construct(string $id, array $valores, string $imagen)
    {
        $this->id = $id;
        $this->valores = $valores;
        $this->imagen = $imagen;
    }
}

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

class Resultado
{
    public $ganancia;
    public $elemento;
    public $linea;
    public $bonus;

    public function __construct(float $ganancia, Elemento $elemento, Linea $linea)
    {
        $this->ganancia = $ganancia;
        $this->elemento = $elemento;
        $this->linea = $linea;
    }

    public function imprimir(): void
    {
        //echo "Ganancia: $this->ganancia \t Ficha: " . $this->elemento->id . "\t Apariciones: " . $this->linea->apariciones . "\t Linea: " . $this->linea->numero . "\n";
        //echo "============================================================\n";
    }

    public static function obtenerGananciaTotal(array $resultados): float
    {
        $total = 0;

        foreach ($resultados['resultados']['ganancias'] as $resultado) {
            $total += $resultado->ganancia;
        }

        if( isset( $resultados['juegosExtra'] ) ) {
            foreach ($resultados['juegosExtra'] as $juegoExtra) {
                
                foreach ($juegoExtra['resultados']['ganancias'] as $resultado) {
                    $total += $resultado->ganancia;
                }

            }
        }

        return $total;
    }
}

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

// VERIFICAR JACKPOTS Y PAGARLOS
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

// VALIDAR SI ES PAGABLE
// CREAR LOGICA DE CUANTAS LINEAS ENTRAN EN JUEGO
// DIVIDIR LA LOGICA EN ARCHIVOS

class Jugador
{
    public $totalApuestas;
    public $totalGanancias;
    public $totalPerdidas;
    
    public function __construct(float $totalApuestas, float $totalGanancias, $totalPerdidas)
    {
        $this->totalApuestas = $totalApuestas;
        $this->totalGanancias = $totalGanancias;
        $this->totalPerdidas = $totalPerdidas;
    }
}


class TragaMonedasStarWars
{
    public $maquina;

    public function __construct(int $numeroLineas)
    {
        $elementos = [
            // new Elemento('at_at', [3 => 60, 4 => 80, 5 => 150], 'at_at'),
            // new Elemento('darth_vader', [3 => 50, 4 => 70, 5 => 140], 'darth_vader'),
            // new Elemento('c3po', [3 => 40, 4 => 60, 5 => 80], 'c3po'),
            // new Elemento('falcon', [3 => 30, 4 => 50, 5 => 70], 'falcon'),
            new Elemento('r2d2', [3 => 15, 4 => 30, 5 => 50], 'r2d2'),
            new Elemento('stormtrooper', [3 => 10, 4 => 15, 5 => 30], 'stormtrooper'),
            new Elemento('tie_ln', [3 => 10, 4 => 15, 5 => 20], 'tie_ln'),


            new Elemento('comodin', [3 => 250, 4 => 500, 5 => 2000], 'yoda'),
            new Elemento('bonus', [3 => 0, 4 => 0, 5 => 0], 'death_star'),
        ];

        $lineas = Linea::getLineas(20);

        $jackpots = [
            new Jackpot(100, 1000, 7)
        ];
        
        $jugador = new Jugador(100, 100, 100);

        $this->maquina = new Maquina($jugador, 50, $lineas, $elementos, $jackpots);
    }

    public function partida($apuesta): array
    {
        return $this->maquina->partida($apuesta);
    }
}
<?php
class Maquina
{
    private const FILAS = 3;
    private const COLUMNAS = 5;
    public $lineas;
    public $elementos;

    public function __construct(array $lineas, array $elementos)
    {
        $this->lineas = $lineas;
        $this->elementos = $elementos;
    }

    private function generarTableroAleatorio(): Tablero
    {
        $filas = self::FILAS;
        $columnas = self::COLUMNAS;

        for ($i=0; $i < $filas; $i++) { 
            for ($j=0; $j < $columnas; $j++) { 
                $tablero[$i][$j] = $this->elementos[ random_int( 0, count( $this->elementos ) - 1 ) ];
            }
        }

        return new Tablero($tablero);
    }

    public function test(): void
    {
        echo json_encode( $this->jugar( new Juego(0.5, 1) ) );
        // foreach ($this->jugar( new Juego(0.5, 1) )['resultados']['ganancias'] as $resultado) {
        //     $resultado->imprimir();
        // }
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
        
        $tablero = $this->generarTableroAleatorio();
        $tablero->imprimir();

        $resultadosConsolidados = $this->coincidenciasConsolidadas($tablero);
        $resultados['resultados'] = $resultadosConsolidados;

        // Si hay bonus en el tablero
        if($resultadosConsolidados['bonus'] > 0) {
            echo "Bonus: " . $resultadosConsolidados['bonus'] . "\n";
            echo "============================================================\n";
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
        echo "Ganancia: $this->ganancia \t Ficha: " . $this->elemento->id . "\t Apariciones: " . $this->linea->apariciones . "\t Linea: " . $this->linea->numero . "\n";
        echo "============================================================\n";
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
            echo "[";
            foreach ($cuadrante as $elemento) {
                echo "\t $elemento->id \t";
            }
            echo "] \n";
        }
        echo "============================================================\n";
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
}
// VALIDAR SI ES PAGABLE
// CREAR LOOP DE BONUS
// VERIFICAR JACKPOTS
class TragaMonedasSimpsons
{
    public $maquina;

    public function __construct()
    {
        $elementos = [
            new Elemento('barnie', [3 => 10, 4 => 15, 5 => 20], 'barnie.png'),
            new Elemento('nelson', [3 => 10, 4 => 15, 5 => 30], 'nelson.png'),
            new Elemento('milhouse', [3 => 15, 4 => 30, 5 => 50], 'milhouse.png'),
            new Elemento('comodin', [3 => 250, 4 => 500, 5 => 2000], 'comodin.png'),
            new Elemento('bonus', [3 => 250, 4 => 500, 5 => 2000], 'bonus.png'),
        ];

        $linea1 = [
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

        $this->maquina = new Maquina($linea1, $elementos);
    }

    public function test(): void
    {
        $this->maquina->test();
    }
}

$simpsons = new TragaMonedasSimpsons();
$simpsons->test();
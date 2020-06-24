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
        // Si las perdidas son negativas, quiere decir que ha ganado demasiado, así que el pago maximo es 0
        $pagoMaximo = $this->jugador->totalPerdidas < 0 ?  0 : ( $this->jugador->totalPerdidas * $this->porcentajePago / 100 );

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

<?php
namespace Tragaperras;

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
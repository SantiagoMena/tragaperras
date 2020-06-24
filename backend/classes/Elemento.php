<?php
namespace Tragaperras;

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

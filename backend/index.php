<?php

include './classes/TragaPerras.php';
include './maquinas/TragaMonedasStarWars.php';

use Tragaperras\Maquinas\TragaMonedasStarWars;

$apuesta = isset($_GET['apuesta']) ? $_GET['apuesta'] : 0.5;
$lineas = isset($_GET['lineas']) ? $_GET['lineas'] : 20;

$starWars = new TragaMonedasStarWars($lineas);

$resultado = $starWars->partida($apuesta);

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}


echo json_encode($resultado);
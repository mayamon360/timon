<?php

use app\models as Model;

/**
 * Endpoint para validar compras
 *
 * @return json
*/
$app->get('/validarOfertas', function() use($app) {
    $p = new Model\Productos; 

    return $app->json($p->validarOfertas());   
});

/**
 * Imprimir ticket
 *
 * @return json
*/
$app->get('/imprimirTicketRapido', function() use($app) {
    $p = new Model\VentasDeMostrador; 
    return $app->json($p->imprimirTicketRapido());  
});

$app->get('/mostrarPruebas', function() use($app) {
    $p = new Model\Pruebas; 
    //return $app->json($p->mostrarPruebas());  
});
<?php

use app\models as Model;


/**
    * Cargar libros nuevos
    *
    * @return json
*/  
$app->get('/cargarNuevos', function() use($app) {
    $d = new Model\Destacados;   

    return $app->json($d->cargarNuevos());
});

/**
    * Cargar libros más vendidos
    *
    * @return json
*/  
$app->get('/cargarMasvendidos', function() use($app) {
    $d = new Model\Destacados;   

    return $app->json($d->cargarMasvendidos());
});

/**
    * Cargar libros por categoría o subcategoría
    *
    * @return json
*/  
$app->get('/cargarLibros', function() use($app) {
    $l = new Model\Libros;   

    return $app->json($l->cargarLibros());
});

/**
    * Cargar libros por autor
    *
    * @return json
*/  
$app->get('/cargarLibrosautor', function() use($app) {
    $a = new Model\Autor;

    return $app->json($a->cargarLibrosautor());
});

/**
    * Cargar libros por editorial
    *
    * @return json
*/  
$app->get('/cargarLibroseditorial', function() use($app) {
    $e = new Model\Editorial;

    return $app->json($e->cargarLibroseditorial());
});

/**
    * Realizar busqueda
    *
    * @return json
*/  
$app->get('/realizarBusqueda', function() use($app) {
    $b = new Model\Busqueda;

    return $app->json($b->realizarBusqueda());
});

/**
    * Realizar busqueda
    *
    * @return json
*/  
$app->get('/realizarBusquedAvanzada', function() use($app) {
    $b = new Model\BusquedaAvanzada;

    return $app->json($b->realizarBusquedAvanzada());
});

/**
    * Cargar datos de usuario
    *
    * @return json
*/  
$app->get('/loadData', function() use($app) {
    $u = new Model\Usuarios;

    return $app->json($u->getOwnerUser("cliente AS name, correoElectronico AS email, rfc, telefono AS phone"));
});

/**
    * Añadir libro a lista de deseos
    *
    * @return json
*/  
$app->get('/guardarDeseo', function() use($app) {
    $l = new Model\Libro;

    return $app->json($l->guardarDeseo());
});

/**
    * Cargar lista de deseos
    *
    * @return json
*/  
$app->get('/cargarDeseos', function() use($app) {
    $l = new Model\Libros;

    return $app->json($l->cargarDeseos());
});

/**
    * Eliminar libro de la lista de deseos
    *
    * @return json
*/  
$app->get('/eliminarDeseo', function() use($app) {
    $l = new Model\Libro;

    return $app->json($l->eliminarDeseo());
});

/**
    * Añadir libro al carrito de compras
    *
    * @return json
*/  
$app->get('/agregarCarrito', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->agregarCarrito());
});

/**
    * Cargar carrito de compras
    *
    * @return json
*/  
$app->get('/cargarCompra', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->cargarCompra());
});

/**
    * Cargar carrito de compras dos
    *
    * @return json
*/  
$app->get('/cargarCompraDos', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->cargarCompraDos());
});

/**
    * Eliminar libro del carrito de compras
    *
    * @return json
*/  
$app->get('/elimiarCarrito', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->elimiarCarrito());
});

/**
    * Modificar cantidad de libro en el carrito de compras
    *
    * @return json
*/  
$app->get('/modificarCantidad', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->modificarCantidad());
});

/**
    * Modificar cantidad de libro en el carrito de compras
    *
    * @return json
*/  
$app->get('/limpiarCarrito', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->limpiarCarrito());
});


/**
    * Modificar el metodo de envio en el carrito de compras
    *
    * @return json
*/  
$app->get('/metodoEnvio', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->metodoEnvio());
});

/**
    * Solicitar vale de OXXO
    * 
    * @ return json
*/
$app->get('/solicitarVale', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->solicitarVale());
});

/**
    * Mostrar las compras Mercado pago de un cliente
    *
    * @return json
*/  
$app->get('/mostrarCompras', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->mostrarCompras());
});

/**
    * Mostrar la compra Mercado pago de un cliente por folio
    *
    * @return json
*/  
$app->get('/mostrarCompra', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->mostrarCompra());
});

/**
    * Mostrar las Stripe compras de un cliente
    *
    * @return json
*/  
$app->get('/mostrarComprasStripe', function() use($app) {
    $c = new Model\Compra;

    return $app->json($c->mostrarComprasStripe());
});


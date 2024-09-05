<?php

/*
 * This file is part of the Ocrend Framewok 3 package.
 *
 * (c) Ocrend Software <info@ocrend.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

use app\models as Model;

/**
    * Inicio de sesión
    *
    * @return json
*/  
$app->post('/login', function() use($app) {
    $u = new Model\Usuarios;   

    return $app->json($u->login());
});

/**
    * Registro de un usuario
    *
    * @return json
*/
$app->post('/register', function() use($app) {
    $u = new Model\Usuarios; 

    return $app->json($u->register());   
});

/**
    * Recuperar contraseña perdida
    *
    * @return json
*/
$app->post('/lostpass', function() use($app) {
    $u = new Model\Usuarios; 

    return $app->json($u->lostpass());   
});

/**
    * Actualizar datos de usuario
    *
    * @return json
*/  
$app->post('/saveData', function() use($app) {
    $u = new Model\Usuarios;

    return $app->json($u->saveData());
});

/**
    * Actualizar datos de envio del usuario
    *
    * @return json
*/  
$app->post('/saveAddress', function() use($app) {
    $u = new Model\Usuarios;

    return $app->json($u->saveAddress());
});

/**
    * Cambiar contraseña de usuario
    *
    * @return json
*/  
$app->post('/changePassword', function() use($app) {
    $u = new Model\Usuarios;

    return $app->json($u->changePassword());
});

$app->post('/contactMessage', function() use($app) {
    $i = new Model\Informacion;

    return $app->json($i->contactMessage());
});


/**
    * Stripe
    *
    * @return json
*/  
$app->post('/create', function() use($app) {
    $u = new Model\Compra;

    return $app->json($u->create());
});



$app->post('/checkCart', function() use($app) {
    $u = new Model\Compra;

    return $app->json($u->checkCart());
});

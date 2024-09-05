<?php

use app\models as Model;

/**
    * Inicio de sesión
    *
    * @return json
*/  
$app->post('/login', function() use($app) {
    $u = new Model\Users;   
    return $app->json($u->login());   
});

/**
    * Validar correo y obtener perfiles
    * 
    * @return json
*/  
$app->post('/validarCorreoAdmin', function() use($app) {
    $a = new Model\Administradores;   
    return $app->json($a->validarCorreoAdmin());   
});

$app->post('/cargarPaises', function() use($app) {
    $c = new Model\Comercio;   
    return $app->json($c->cargarPaises()); 
});

/**
    * Registro de un usuario
    *
    * @return json
*/
$app->post('/register', function() use($app) {
    $u = new Model\Users; 
    return $app->json($u->register());   
});

/**
    * Recuperar contraseña perdida
    *
    * @return json
*/
$app->post('/lostpass', function() use($app) {
    $u = new Model\Users; 
    return $app->json($u->lostpass());   
});



#   Endpoint ------------------------------------------------------------------ Home

/**
    * Obtener ventas
    *
    * @return json
*/
$app->post('/cargarVentas', function() use($app) {
    $h = new Model\Home; 
    return $app->json($h->cargarVentas());   
});
/**
    * Obtener visitas
    *
    * @return json
*/
$app->post('/cargarVisitas', function() use($app) {
    $h = new Model\Home; 
    return $app->json($h->cargarVisitas());   
});
/**
    * Cargar libros mas vendidos
    *
    * @return json
*/
$app->post('/cargarMasVendidos', function() use($app) {
    $h = new Model\Home; 
    return $app->json($h->cargarMasVendidos());   
});
/**
    * Obtener ventas por mes
    *
    * @return json
*/
$app->post('/ventasPorMes', function() use($app) {
    $h = new Model\Home; 
    return $app->json($h->ventasPorMes());   
});
/**
    * Obtener compras por mes
    *
    * @return json
*/
$app->post('/comprasPorMes', function() use($app) {
    $h = new Model\Home; 
    return $app->json($h->comprasPorMes());   
});




#   Endpoint ------------------------------------------------------------------ Comercio
/**
    * Editar datos de comercio
    *
    * @return json
*/
$app->post('/cambiarDatos', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->cambiarDatos());   
});

/**
 * Editar logotipo o icono
 *
 * @return json
*/
$app->post('/cambiarLogo', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->cambiarLogo());   
});

/**
 * Editar colores de plantilla
 *
 * @return json
*/
$app->post('/cambiarColores', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->cambiarColores());   
});
/**
 * Editar redes sociales
 *
 * @return json
*/
$app->post('/cambiarRS', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->cambiarRS());   
});
/**
 * Editar códigos javascript
 *
 * @return json
*/
$app->post('/cambiarCodigos', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->cambiarCodigos());   
});
/**
 * Editar información del comercio
 *
 * @return json
*/
$app->post('/cambiarInformacion', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->cambiarInformacion());   
});
/**
 * Cargar sliders
 *
 * @return json
*/
$app->post('/slider', function() use($app) {
    $c = new Model\Comercio; 
    return $app->json($c->datosPlantilla());   
});




#   Endpoint ------------------------------------------------------------------ Slider
/**
 * Crear slider
 *
 * @return json
*/
$app->post('/crearSlider', function() use($app) {
    $s = new Model\Slider; 
    return $app->json($s->crearSlider());   
});
/**
 * Cambiar orden de sliders
 *
 * @return json
*/
$app->post('/ordenarSlider', function() use($app) {
    $s = new Model\Slider; 
    return $app->json($s->ordenarSlider());   
});
/**
 * Editar slider
 *
 * @return json
*/
$app->post('/actualizarSlider', function() use($app) {
    $s = new Model\Slider; 
    return $app->json($s->actualizarSlider());   
});
/**
 * Eliminar slider
 *
 * @return json
*/
$app->post('/eliminarSlider', function() use($app) {
    $s = new Model\Slider; 
    return $app->json($s->eliminarSlider());   
});




#   Endpoint ------------------------------------------------------------------ Categorias
/**
 * Mostrar
 *
 * @return json
*/
$app->post('/mostrarCategorias', function() use($app) {
    $c = new Model\Categorias; 
    return $app->json($c->mostrarCategorias());   
});
/**
 * Estado                                                    (VER SI SE ELIMINA)
 *
 * @return json
*/
$app->post('/estadoCategoria', function() use($app) {
    $c = new Model\Categorias; 
    return $app->json($c->estadoCategoria());   
});
/**
 * Agregar
 *
 * @return json
*/
$app->post('/agregarCategoria', function() use($app) {
    $c = new Model\Categorias; 
    return $app->json($c->agregarCategoria());   
});
/**
 * Cargar en formulario
 *
 * @return json
*/
$app->post('/obtenerCategoria', function() use($app) {
    $c = new Model\Categorias; 
    return $app->json($c->obtenerCategoria());   
});
/**
 * Editar
 *
 * @return json
*/
$app->post('/editarCategoria', function() use($app) {
    $c = new Model\Categorias; 
    return $app->json($c->editarCategoria());   
});
/**
 * Eliminar
 *
 * @return json
*/
$app->post('/eliminarCategoria', function() use($app) {
    $c = new Model\Categorias; 
    return $app->json($c->eliminarCategoria());   
});




#   Endpoint ------------------------------------------------------------------ Subcategorias
/**
 * Mostrar
 *
 * @return json
*/
$app->post('/mostrarSubcategorias', function() use($app) {
    $s = new Model\Subcategorias; 
    return $app->json($s->mostrarSubcategorias());   
});
/**
 * Estado                                                    (VER SI SE ELIMINA)
 *
 * @return json
*/
$app->post('/estadoSubcategoria', function() use($app) {
    $s = new Model\Subcategorias; 
    return $app->json($s->estadoSubcategoria());   
});
/**
 * Agregar
 *
 * @return json
*/
$app->post('/agregarSubcategoria', function() use($app) {
    $s = new Model\Subcategorias; 
    return $app->json($s->agregarSubcategoria());   
});
/**
 * Cargar en formulario
 *
 * @return json
*/
$app->post('/obtenerSubcategoria', function() use($app) {
    $s = new Model\Subcategorias; 
    return $app->json($s->obtenerSubcategoria());   
});
/**
 * Editar
 *
 * @return json
*/
$app->post('/editarSubcategoria', function() use($app) {
    $s = new Model\Subcategorias; 
    return $app->json($s->editarSubcategoria());   
});
/**
 * Eliminar
 *
 * @return json
*/
$app->post('/eliminarSubcategoria', function() use($app) {
    $s = new Model\Subcategorias; 
    return $app->json($s->eliminarSubcategoria());   
});
/**
 * Cargar en select
 *
 * @return json
*/
$app->post('/selectSubcategorias', function() use($app) {
    $s = new Model\Subcategorias;  
    return $app->json($s->selectSubcategorias());   
});




#   Endpoint ------------------------------------------------------------------ Productos
/**
 * Mostrar
 *
 * @return json
*/
$app->post('/mostrarProductos', function() use($app) {
    $p = new Model\Productos; 
    return $app->json($p->mostrarProductos());   
});
/**
 * Costos
 *
 * @return json
*/
$app->post('/costosProducto', function() use($app) {
    $p = new Model\Productos; 
    return $app->json($p->costosProducto());   
});
/**
 * Stock
 *
 * @return json
*/
$app->post('/stockProducto', function() use($app) {
    $p = new Model\Productos; 
    return $app->json($p->stockProducto());   
});
/**
 * Movimientos
 *
 * @return json
*/
$app->post('/movimientosProducto', function() use($app) {
    $p = new Model\Productos; 
    return $app->json($p->movimientosProducto());
});
/**
 * Descontinuar
 *
 * @return json
*/
$app->post('/descontinuarProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->descontinuarProducto());   
});
/**
 * Activar
 *
 * @return json
*/
$app->post('/activarProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->activarProducto());   
});
/**
 * Liquidacion
 *
 * @return json
*/
$app->post('/liquidacion', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->liquidacion());   
});
/**
 * Novedad
 *
 * @return json
*/
$app->post('/novedad', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->novedad());   
});
/**
 * Anticipo                                                  (VER SI SE ELIMINA)
 *
 * @return json
*/
$app->post('/anticipoProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->anticipoProducto());   
});
/**
 * Generar código aleatorio
 *
 * @return json
*/
$app->post('/generarCodigoProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->generarCodigoProducto());   
});
/**
 * Agregar
 *
 * @return json
*/
$app->post('/agregarProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->agregarProducto());   
});
/**
 * Cargar en formulario 
 *
 * @return json
*/
$app->post('/obtenerProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->obtenerProducto());   
});
/**
 * Editar
 *
 * @return json
*/
$app->post('/editarProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->editarProducto());   
});
/**
 * Eliminar
 *
 * @return json
*/
$app->post('/eliminarProducto', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->eliminarProducto());   
});
/**
 * Buscar en punto de venta
 *
 * @return json
*/
$app->post('/buscarProductoVenta', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->buscarProductoVenta());   
});
/**
 * Mostrar en tabla de punto de venta
 *
 * @return json
*/
$app->post('/mostrarProductosPV', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->mostrarProductosPV());   
});
/**
 * Buscar en registrar compras
 *
 * @return json
*/
$app->post('/buscarProductoCompra', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->buscarProductoCompra());   
});
/**
 * Mostrar en tabla de registrar compras
 *
 * @return json
*/
$app->post('/mostrarProductosC', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->mostrarProductosC());   
});
/**
 * Buscar en crédito
 *
 * @return json
*/
$app->post('/buscarProductoCredito', function() use($app) {
    $r = new Model\Productos;  
    return $app->json($r->buscarProductoCredito());   
});

/**
 * Mostrar en tabla de pedidos compras
 *
 * @return json
*/
$app->post('/mostrarProductosPedidos', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->mostrarProductosPedidos());   
});

/**
 * Obtener editoriales                     (VER COMO MUDAR A MODELO EDITORIALES)
 *
 * @return json
*/
$app->post('/obtenerEditoriales', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->obtenerEditoriales());   
});

/**
 * Obtener autores                             (VER COMO MUDAR A MODELO AUTORES)
 *
 * @return json
*/
$app->post('/obtenerAutores', function() use($app) {
    $p = new Model\Productos;  
    return $app->json($p->obtenerAutores());   
});




#   Endpoint Banners
#------------------------------------------------------------
/**
 * Mostrar banners
 *
 * @return json
*/
$app->post('/mostrarBanners', function() use($app) {
    $b = new Model\Banners; 
    return $app->json($b->mostrarBanners());   
});
/**
 * Editar estado de banner
 *
 * @return json
*/
$app->post('/estadoBanner', function() use($app) {
    $b = new Model\Banners; 
    return $app->json($b->estadoBanner());   
});
/**
 * Obtener datos de banner a editar
 *
 * @return json
*/
$app->post('/obtenerBanner', function() use($app) {
    $b = new Model\Banners; 

    return $app->json($b->obtenerBanner());   
});
/**
 * Editar banner
 *
 * @return json
*/
$app->post('/editarBanner', function() use($app) {
    $b = new Model\Banners; 
    return $app->json($b->editarBanner());   
});




#   Endpoint VentasenLinea
#------------------------------------------------------------
/**
 * Mostrar ventas en linea
 *
 * @return json
*/
$app->post('/mostrarVentas', function() use($app) {
    $v = new Model\ventasEnLinea; 
    return $app->json($v->mostrarVentas());   
});

/**
 * Editar estado de la venta en linea
 *
 * @return json
*/
$app->post('/cambiarEstatus', function() use($app) {
    $v = new Model\ventasEnLinea; 
    return $app->json($v->cambiarEstatus());   
});

/**
 * Desplegar la información detallada de una compra en linea
 *
 * @return json
*/
$app->post('/verCompra', function() use($app) {
    $v = new Model\ventasEnLinea; 
    return $app->json($v->verCompra());   
});

/**
 * Verificar si hay compras en linea nuevas
 *
 * @return json
*/
$app->post('/checkShopping', function() use($app) {
    $v = new Model\ventasEnLinea; 
    return $app->json($v->checkShopping());   
});



#   Endpoint Visitas
#------------------------------------------------------------
/**
 * Mostrar vistas
 *
 * @return json
*/
$app->post('/mostrarVisitas', function() use($app) {
    $v = new Model\Visitas; 
    return $app->json($v->mostrarVisitas());   
});




#   Endpoint Usuarios
#------------------------------------------------------------
/**
 * Mostrar usuarios
 *
 * @return json
*/
$app->post('/mostrarUsuarios', function() use($app) {
    $u = new Model\Usuarios; 
    return $app->json($u->mostrarUsuarios());   
});
/**
 * Editar estado de usuario
 *
 * @return json
*/
$app->post('/estadoUsuario', function() use($app) {
    $u = new Model\Usuarios; 
    return $app->json($u->estadoUsuario());   
});




#   Endpoint Administradores
#------------------------------------------------------------
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/mostrarAdministradores', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->mostrarAdministradores());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/validarAdministrador', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->validarAdministrador());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/estadoAdministrador', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->estadoAdministrador());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/agregarAdministrador', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->agregarAdministrador());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/obtenerAdministrador', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->obtenerAdministrador());   
});

/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/editarAdministrador', function() use($app) {
    $p = new Model\Administradores; 

    return $app->json($p->editarAdministrador());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/eliminarAdministrador', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->eliminarAdministrador());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/cargarCuenta', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->cargarCuenta());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/cargarHistorial', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->cargarHistorial());   
});
/**
 * Endpoint para administradores
 *
 * @return json
*/
$app->post('/cambiarDatosCuenta', function() use($app) {
    $p = new Model\Administradores; 
    return $app->json($p->cambiarDatosCuenta());   
});
/**
 * Endpoint
 *
 * @return json
*/
$app->post('/checkSession', function() use($app) {
    $a = new Model\Administradores; 
    return $app->json($a->checkSession());   
});




#   Endpoint Perfiles
#------------------------------------------------------------
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/mostrarPerfiles', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->mostrarPerfiles());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/estadoPerfil', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->estadoPerfil());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/validarPerfil', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->validarPerfil());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/agregarPerfil', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->agregarPerfil());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/obtenerPerfil', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->obtenerPerfil());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/editarPerfil', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->editarPerfil());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/eliminarPerfil', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->eliminarPerfil());   
});
/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/perfilesAdcionales', function() use($app) {
    $p = new Model\Perfiles; 
    return $app->json($p->perfilesAdcionales());   
});





/**
 * Endpoint para menu
 *
 * @return json
*/
$app->post('/cargarModulos', function() use($app) {
    $p = new Model\Menu; 

    return $app->json($p->menu());   
});





/**
 * Endpoint
 *
 * @return json
*/
$app->post('/eventos', function() use($app) {
    $a = new Model\Actividad; 
    return $app->json($a->eventos());   
});
/**
 * Endpoint
 *
 * @return json
*/
$app->post('/notificaciones', function() use($app) {
    $a = new Model\Actividad; 
    return $app->json($a->notificaciones());   
});





/**
 * Endpoint para mi_cuenta
 *
 * @return json
*/
$app->post('/mi_cuenta', function() use($app) {
    $m = new Model\Mi_cuenta; 
    return $app->json($m->foo());   
});




#   Endpoint Clientes
#------------------------------------------------------------
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/mostrarClientes', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->mostrarClientes());   
});
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/estadoCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->estadoCliente());   
});
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/agregarCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->agregarCliente());   
});
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/validarCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->validarCliente());   
});

/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/obtenerCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->obtenerCliente());   
});
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/obtenerClientes', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->obtenerClientes());   
});
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/editarCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->editarCliente());   
});

/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/eliminarCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->eliminarCliente());   
});

/**
 * Obtener editoriales de cliente credito
 *
 * @return json
*/
$app->post('/cargarEditorialesCliente', function() use($app) {
    $c = new Model\Clientes; 
    return $app->json($c->cargarEditorialesCliente());   
});




#   Endpoint Proveedores
#------------------------------------------------------------
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/mostrarProveedores', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->mostrarProveedores());   
});
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/estadoProveedor', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->estadoProveedor());   
});
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/validarProveedor', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->validarProveedor());   
});
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/agregarProveedor', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->agregarProveedor());   
});
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/obtenerProveedor', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->obtenerProveedor());   
});
/**
 * Endpoint para clientes
 *
 * @return json
*/
$app->post('/obtenerProveedores', function() use($app) {
    $c = new Model\Proveedores; 
    return $app->json($c->obtenerProveedores());   
});
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/editarProveedor', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->editarProveedor());   
});
/**
 * Endpoint para proveedores
 *
 * @return json
*/
$app->post('/eliminarProveedor', function() use($app) {
    $p = new Model\Proveedores; 
    return $app->json($p->eliminarProveedor());   
});




#   Endpoint PuntoDeVenta
#------------------------------------------------------------
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/datosTicket', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->datosTicket());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/cargarListaVenta', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->cargarListaVenta());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/agregarProductoListaVenta', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->agregarProductoListaVenta());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/modificarCantidad', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->modificarCantidad());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/agregarDescuento', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->agregarDescuento());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/agregarImpuestoComision', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->agregarImpuestoComision());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/quitarProductoListaVenta', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->quitarProductoListaVenta());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/vaciarListaVenta', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->vaciarListaVenta());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/confirmarVenta', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->confirmarVenta());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/consignacionSalida', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->consignacionSalida());   
});
/**
 * Endpoint para puntodeventa
 *
 * @return json
*/
$app->post('/ajusteSalida', function() use($app) {
    $p = new Model\PuntoDeVenta; 
    return $app->json($p->ajusteSalida());   
});




#   Endpoint VentasDeMostrador
#------------------------------------------------------------
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/mostrarVentasMostrador', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->mostrarVentasMostrador());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/mostrarListaVenta', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->mostrarListaVenta());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/cargarListaVentaEditar', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->cargarListaVentaEditar());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/devolucionPorItem', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->devolucionPorItem());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/devolucionPorFolio', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->devolucionPorFolio());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/agregarDescuentoEditar', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->agregarDescuentoEditar());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/devolucionPorCantidad', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->devolucionPorCantidad());   
});
/**
 * Endpoint para ventasdemostrador
 *
 * @return json
*/
$app->post('/agregarImpuestoComisionEdicion', function() use($app) {
    $v = new Model\VentasDeMostrador; 
    return $app->json($v->agregarImpuestoComisionEdicion());   
});




#   Endpoint ReporteVentasMostrador
#------------------------------------------------------------
/**
 * Endpoint para reporteventasmostrador
 *
 * @return json
*/
$app->post('/cargarVentasMostrador', function() use($app) {
    $r = new Model\ReporteVentasMostrador; 
    return $app->json($r->cargarVentasMostrador());   
});
/**
 * Endpoint para reporteventasmostrador
 *
 * @return json
*/
$app->post('/cargarMasVendidosMostrador', function() use($app) {
    $r = new Model\ReporteVentasMostrador; 
    return $app->json($r->cargarMasVendidosMostrador());   
});
/**
 * Endpoint para reporteventasmostrador
 *
 * @return json
*/
$app->post('/cargarTopClientes', function() use($app) {
    $r = new Model\ReporteVentasMostrador; 
    return $app->json($r->cargarTopClientes());   
});




#   Endpoint Caja
#------------------------------------------------------------
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/cargarCajaPorDia', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->cargarCajaPorDia());   
});
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/obtenerFormularioCaja', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->obtenerFormularioCaja());   
});
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/registrarMontoInicial', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->registrarMontoInicial());   
});
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/cerrarCaja', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->cerrarCaja());   
});
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/reaperturarCaja', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->reaperturarCaja());   
});
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/obtenerFormularioMovimientos', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->obtenerFormularioMovimientos());   
});
/**
 * Endpoint para caja
 *
 * @return json
*/
$app->post('/registrarMovimiento', function() use($app) {
    $c = new Model\Caja; 
    return $app->json($c->registrarMovimiento());   
});




#   Endpoint PedidosCompras
#------------------------------------------------------------
/**
 * agregarPedido
*/
$app->post('/agregarPedido', function() use($app) {
    $p = new Model\PedidosCompras; 
    return $app->json($p->agregarPedido());   
});
/**
 * cargarListaPedido
 *
*/
$app->post('/cargarListaPedido', function() use($app) {
    $p = new Model\PedidosCompras; 
    return $app->json($p->cargarListaPedido());   
});
/**
 * nuevoPedido
 *
*/
$app->post('/nuevoPedido', function() use($app) {
    $p = new Model\PedidosCompras; 
    return $app->json($p->nuevoPedido());   
});
/**
 * productoPedidoFormulario
 *
*/
$app->post('/productoPedidoFormulario', function() use($app) {
    $p = new Model\PedidosCompras; 
    return $app->json($p->productoPedidoFormulario());   
});





#   Endpoint Pedidos
#------------------------------------------------------------
/**
 * mostrarPedidos
 *
 * @return json
*/
$app->post('/mostrarPedidos', function() use($app) {
    $p = new Model\Pedidos; 
    return $app->json($p->mostrarPedidos());   
});
/**
 * mostrarListaPedido
 *
 * @return json
*/
$app->post('/mostrarListaPedido', function() use($app) {
    $p = new Model\Pedidos; 
    return $app->json($p->mostrarListaPedido());   
});

/**
 * cancelarPedido
 *
 * @return json
*/
$app->post('/cancelarPedido', function() use($app) {
    $p = new Model\Pedidos; 
    return $app->json($p->cancelarPedido());   
});

/**
 * cargarLibroPedido
 *
 * @return json
*/
$app->post('/cargarLibroPedido', function() use($app) {
    $p = new Model\Pedidos; 
    return $app->json($p->cargarLibroPedido());   
});

/**
 * confirmarEntrega
 *
 * @return json
*/
$app->post('/confirmarEntrega', function() use($app) {
    $p = new Model\Pedidos; 
    return $app->json($p->confirmarEntrega());   
});









/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/agregarProductoListaCompra', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->agregarProductoListaCompra());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/cargarListaCompras', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->cargarListaCompras());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/quitarProductoListaCompra', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->quitarProductoListaCompra());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/vaciarListaCompra', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->vaciarListaCompra());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/modificarCantidadCompra', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->modificarCantidadCompra());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/modificarCostoCompra', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->modificarCostoCompra());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/modificarPrecioVenta', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->modificarPrecioVenta());   
});
/**
 * Endpoint para RegistrarCompras
 *
 * @return json
*/
$app->post('/confirmarCompra', function() use($app) {
    $r = new Model\RegistrarCompras; 
    return $app->json($r->confirmarCompra());   
});
/**
 * Endpoint para Registrar Compras
 *
 * @return json
*/
$app->post('/consignacionEntrada', function() use($app) {
    $r = new Model\RegistrarCompras;  
    return $app->json($r->consignacionEntrada());   
});
/**
 * Endpoint para Registrar Compras
 *
 * @return json
*/
$app->post('/ajusteEntrada', function() use($app) {
    $r = new Model\RegistrarCompras;  
    return $app->json($r->ajusteEntrada());   
});





/**
 * Endpoint para compras
 *
 * @return json
*/
$app->post('/mostrarCompras', function() use($app) {
    $c = new Model\Compras; 
    return $app->json($c->mostrarCompras());   
});
/**
 * Endpoint para compras
 *
 * @return json
*/
$app->post('/mostrarListaCompra', function() use($app) {
    $c = new Model\Compras; 
    return $app->json($c->mostrarListaCompra());   
});





/**
 * Endpoint para reportecomprasentradas
 *
 * @return json
*/
$app->post('/cargarCompras', function() use($app) {
    $r = new Model\ReporteComprasEntradas; 
    return $app->json($r->cargarCompras());   
});
/**
 * Endpoint para reportecomprasentradas
 *
 * @return json
*/
$app->post('/cargarMasComprados', function() use($app) {
    $r = new Model\ReporteComprasEntradas; 
    return $app->json($r->cargarMasComprados());   
});
/**
 * Endpoint para reportecomprasentradas
 *
 * @return json
*/
$app->post('/cargarTopProveedores', function() use($app) {
    $r = new Model\ReporteComprasEntradas; 
    return $app->json($r->cargarTopProveedores());   
});





/**
 * Endpoint para perfiles
 *
 * @return json
*/
$app->post('/almacenesAdcionales', function() use($app) {
    $a = new Model\Almacenes; 
    return $app->json($a->almacenesAdcionales());   
});





/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/mostrarCreditos', function() use($app) {
    $c = new Model\Creditos; 
    return $app->json($c->mostrarCreditos());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/cancelarCredito', function() use($app) {
    $c = new Model\Creditos; 
    return $app->json($c->cancelarCredito());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/mostrarListaCredito', function() use($app) {
    $c = new Model\Creditos; 
    return $app->json($c->mostrarListaCredito());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/cargarListaCreditoEditar', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->cargarListaCreditoEditar());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/devolucionCredito', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->devolucionCredito());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/cerrarCredito', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->cerrarCredito());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/abonarCredito', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->abonarCredito());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/cargarDevoluciones', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->cargarDevoluciones());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/cargarPagos', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->cargarPagos());   
});
/**
 * Endpoint para creditos
 *
 * @return json
*/
$app->post('/cargarSalidas', function() use($app) {
    $c = new Model\Creditos;
    return $app->json($c->cargarSalidas());   
});





/**
 * Endpoint para registrarcredito
 *
 * @return json
*/
$app->post('/cargarListaCredito', function() use($app) {
    $r = new Model\RegistrarCredito; 

    return $app->json($r->cargarListaCredito());   
});
/**
 * Endpoint para registrarcredito
 *
 * @return json
*/
$app->post('/agregarProductoListaCredito', function() use($app) {
    $r = new Model\RegistrarCredito; 
    return $app->json($r->agregarProductoListaCredito());   
});
/**
 * Endpoint para Modificar Cantidad Credito
 *
 * @return json
*/
$app->post('/modificarCantidadCredito', function() use($app) {
    $r = new Model\RegistrarCredito; 
    return $app->json($r->modificarCantidadCredito());   
});
/**
 * Endpoint para Credito
 *
 * @return json
*/
$app->post('/agregarDescuentoCredito', function() use($app) {
    $p = new Model\RegistrarCredito; 
    return $app->json($p->agregarDescuentoCredito());   
});
/**
 * Endpoint para registrarcredito
 *
 * @return json
*/
$app->post('/quitarProductoListaCredito', function() use($app) {
    $r = new Model\RegistrarCredito; 
    return $app->json($r->quitarProductoListaCredito());   
});
/**
 * Endpoint para registrarcredito
 *
 * @return json
*/
$app->post('/vaciarListaCredito', function() use($app) {
    $r = new Model\RegistrarCredito; 
    return $app->json($r->vaciarListaCredito());   
});
/**
 * Endpoint para registrarcredito
 *
 * @return json
*/
$app->post('/confirmarCredito', function() use($app) {
    $r = new Model\RegistrarCredito;  
    return $app->json($r->confirmarCredito());   
});





/**
 * Endpoint para reportecreditos (OJO aun no se trabaja esta funcion)
 *
 * @return json
*/
$app->post('/reportecreditos', function() use($app) {
    $r = new Model\ReporteCreditos; 

    return $app->json($r->foo());   
});






















/**
 * Endpoint para editoriales
 *
 * @return json
*/
$app->post('/mostrarEditoriales', function() use($app) {
    $e = new Model\Editoriales; 

    return $app->json($e->mostrarEditoriales());   
});
/**
 * Endpoint para editoriales
 *
 * @return json
*/
$app->post('/creditoEditorial', function() use($app) {
    $e = new Model\Editoriales; 

    return $app->json($e->creditoEditorial());   
});
/**
 * Endpoint para editoriales
 *
 * @return json
*/
$app->post('/estadoEditorial', function() use($app) {
    $e = new Model\Editoriales; 

    return $app->json($e->estadoEditorial());   
});
/**
 * Agregar editorial
 *
 * @return json
*/
$app->post('/agregarEditoriales', function() use($app) {
    $p = new Model\Editoriales;  

    return $app->json($p->agregarEditoriales());   
});
/**
 * Cargar datos de editorial para editar
 *
 * @return json
*/
$app->post('/obtenerEditorial', function() use($app) {
    $c = new Model\Editoriales; 
    return $app->json($c->obtenerEditorial());   
});
/**
 * Editar editorial
 *
 * @return json
*/
$app->post('/editarEditorial', function() use($app) {
    $c = new Model\Editoriales; 
    return $app->json($c->editarEditorial());   
});
/**
 * Eliminar editorial
 *
 * @return json
*/
$app->post('/eliminarEditorial', function() use($app) {
    $c = new Model\Editoriales; 
    return $app->json($c-> eliminarEditorial());   
});


/**
 * Endpoint para autores
 *
 * @return json
*/
$app->post('/mostrarAutores', function() use($app) {
    $e = new Model\Autores; 

    return $app->json($e->mostrarAutores());   
});
/**
 * Endpoint para autores
 *
 * @return json
*/
$app->post('/estadoAutor', function() use($app) {
    $e = new Model\Autores; 

    return $app->json($e->estadoAutor());   
});
/**
 * Endpoint para autores
 *
 * @return json
*/
$app->post('/agregarAutores', function() use($app) {
    $e = new Model\Autores; 

    return $app->json($e->agregarAutores());   
});
/**
 * Endpoint para autores
 *
 * @return json
*/
$app->post('/obtenerAutor', function() use($app) {
    $e = new Model\Autores; 

    return $app->json($e-> obtenerAutor());   
});
/**
 * Endpoint para autores
 *
 * @return json
*/
$app->post('/editarAutor', function() use($app) {
    $e = new Model\Autores; 

    return $app->json($e->editarAutor());   
});
/**
 * Endpoint para autores
 *
 * @return json
*/
$app->post('/eliminarAutor', function() use($app) {
    $e = new Model\Autores; 

    return $app->json($e->eliminarAutor());   
});
<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador home/
 * 
 * Panel de control (pagina principal del sistema)
 * 
*/
class homeController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        
        //  ussers_logged en true para que lo pueden ver los usuarios logeados
        parent::__construct($router, $configController = [
        	'users_logged' => true
        ]);
        
        global $config;

        (new Model\Caja)->registrarMonto();
        
        // llamar modelo Home
        $h = new Model\Home;
        
        /**
         * OPCIONES
         * Perfil 4 Administración de sucursal
        */
        // Obtener el total en inventario
        $totalInventario = $h->totalInventario();
        // Obtener el total en compras del mes actual
        $totalCompras = $h->totalCompras();
        // Obtener el total en ventas del mes actual
        $totalVentas = $h->totalVentas();
        // Obtener monto actual en caja
        $montoTotalCaja = (new Model\Caja)->montoTotalCaja(date('Y-m-d'));
        // Obtener las cuentas por cobrar pendientes
        $cuentasPorCobrar = $h->cuentasPorCobrar();
        // Obtener el total de categorias
        $totalCategorias = ($h->totalCategorias()) ? $h->totalCategorias() : 0; 
        // Obtener el total de subcategorias
        $totalSubcategorias = ($h->totalSubcategorias()) ? $h->totalSubcategorias() : 0;
        // Obtener el total de editoriales
        $totalEditoriales = ($h->totalEditoriales()) ? $h->totalEditoriales() : 0;
        // Obtener el total de autores
        $totalAutores = ($h->totalAutores()) ? $h->totalAutores() : 0; 
        // Obtener el total de productos
        $totalProductos = ($h->totalProductos()) ? $h->totalProductos() : 0; 
        // Obtener el total de clientes
        $totalClientes = ($h->totalClientes()) ? $h->totalClientes() : 0; 
        // Obtener el total de proveedores
        $totalProveedores = ($h->totalProveedores()) ? $h->totalProveedores() : 0; 
        
        /**
         * OPCIONES
         * Perfil 2 Ajustes y estadísticas del sitio web
        */
        // Obtener el total de sliders
        $totalSliders = ($h->totalSliders()) ? $h->totalSliders() : 0; 
        // Obtener el total de banners
        $totalBanners = ($h->totalBanners()) ? $h->totalBanners() : 0; 
        // Obtener el total de visitas (puede que se elimine)
        $totalVisitas = ($h->totalVisitas()) ? $h->totalVisitas() : 0;
        // Obtener el total de usuarios
        $totalUsuarios = ($h->mostrarUsuarios("id")) ? count($h->mostrarUsuarios("id")) : 0; 

        $this->template->addExtension(new Helper\Strings);

        $this->template->display('home/home', array(
            'totalInventario' => $totalInventario,
            'totalCompras' => $totalCompras,
            'totalVentas' => $totalVentas,
            'montoTotalCaja' => $montoTotalCaja,
            'cuentasPorCobrar' => $cuentasPorCobrar,
            'totalCategorias' => $totalCategorias,
            'totalSubcategorias' => $totalSubcategorias,
            'totalEditoriales' => $totalEditoriales,
            'totalAutores' => $totalAutores,
            'totalProductos' => $totalProductos,
            'totalClientes' => $totalClientes,
            'totalProveedores' => $totalProveedores,
            
            'totalSliders' => $totalSliders,
            'totalBanners' => $totalBanners,
            'totalVisitas' => $totalVisitas,
            'totalUsuarios' => $totalUsuarios,
            'masVendidos' => $h->mostrarProductos("ventas", 8),
            'todosProductos' => $h->mostrarProductos("id"),
            'ultimosUsuarios' => $h->mostrarUsuarios("fecha", 8),
            'ultimosProductos' => $h->mostrarProductos("id", 5),
            
            'url' => $config['build']['url']
        ));

    }
    
}
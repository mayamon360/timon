<?php

namespace app\models;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Models\Models;
use Ocrend\Kernel\Models\IModels;
use Ocrend\Kernel\Models\ModelsException;
use Ocrend\Kernel\Models\Traits\DBModel;
use Ocrend\Kernel\Router\IRouter;

/**
 * Modelo Destacados
 */
class Destacados extends Models implements IModels {
    use DBModel;
    
    public function autores($id){
        $stmt = $this->db->select('*', "productos", null, "autores=$id", 20, "ORDER BY RAND()");
        if($stmt){
            global $config;
            $html = '';
            $l = new Model\Libro;
            $a = new Model\Autor;
            foreach($stmt as $key => $mi_libro){
    
                $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                $precio = $libro_precio_oferta[0];
                $oferta = $libro_precio_oferta[1];
                
                $titulo_completo = $mi_libro['producto'].' '.$mi_libro['leyenda'];
                if(strlen($titulo_completo) > 40){
                    $titulo_completo = substr($titulo_completo, 0, 40).'...';
                }
    
                $autores = $a->autoresHtml($mi_libro['autores']);
                
                $intervalo = $l->libroNuevo($mi_libro);
                if($mi_libro['novedad'] == 1 && $intervalo <= 30){
                    $html .= '<div class="card nuevo text-center mt-4 bg-white tarjeta_libro" data-label="NUEVO">';   
                }else{
                    $html .= '<div class="card text-center mt-4 bg-white tarjeta_libro">';
                }
                
                $imagen=$mi_libro['imagen'];
    
                $html .= '
                    <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                        <a href="'.$config['build']['url'].'libro/'.$mi_libro['ruta'].'">
                            <img class="card-img" src="'.$config['build']['url'].$imagen.'">
                            '.$oferta.'
                        </a>
                    </div>
                    <span class="d-block mt-3 px-3">
                        <a class="text-truncate enlace_negro" href="'.$config['build']['url'].'libro/'.$mi_libro['ruta'].'">'.$titulo_completo.'</a>
                    </span>
                    <span class="d-block">
                        <p class="text-truncate m-0 px-3 autor">'.$autores.'</p>
                    </span>
                    <div class="row flex align-items-center my-2 precio">
                        '.$precio.'
                    </div>
                    <!--
                    <div class="card-footer px-3 py-2">
                        <div class="row d-flex align-items-center">
                            <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$mi_libro['id'].'">';
                                if($this->id_user === null){
                                    $html .= '<a href="'.$config['build']['url'].'autenticacion" class="love" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                }else{
                                    $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto={$mi_libro['id']}",1);
                                    if($stmt){
                                        $html .= '<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>';
                                    }else{
                                        $html .= '<a href="#" class="love agregar_deseo" id="'.$mi_libro['id'].'" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                    }
                                }
                            $html .= '
                            </div>
                            <div class="col-9 pl-1 text-right">';
                            if($mi_libro['stock'] > 0){
                                $html .= '
                                <button type="button" idLibro="'.$mi_libro['id'].'" class="btn btn-sm btn-icon boton_color agregar_carrito">
                                    <span class="btn-inner--text d-none d-sm-inline">Agregar</span>
                                    <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
                                </button>';
                            }
                            $html .= '    
                            </div>
                        </div>                                                                                                                 
                    </div>-->
                </div>';
            }
        }
        return $html;
    }
    
    public function slideNovedades(){
        $this->db->query("DROP VIEW IF EXISTS novedades");
        $this->db->query("CREATE VIEW novedades AS 
                            SELECT *, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar, (ventasMostrador + ventas) as ventasTotales
                            FROM productos
                            WHERE estado = 1 AND novedad = 1
                            ORDER BY id DESC 
                            LIMIT 50");
        /*$this->db->query("CREATE VIEW novedades AS 
                            SELECT *, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar, (ventasMostrador + ventas) as ventasTotales
                            FROM productos
                            WHERE estado = 1 AND novedad = 1 AND fechaRegistro BETWEEN CURDATE() - INTERVAL 30 DAY AND NOW()
                            ORDER BY id DESC 
                            LIMIT 50");*/
        $stmt = $this->db->select('*', "novedades", null, null, 20);
        if($stmt){
            global $config;
            $html = '';
            $l = new Model\Libro;
            $a = new Model\Autor;
            foreach($stmt as $key => $mi_libro){
        
                $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                $precio = $libro_precio_oferta[0];
                $oferta = $libro_precio_oferta[1];
                
                $titulo_completo = $mi_libro['producto'].' '.$mi_libro['leyenda'];
                if(strlen($titulo_completo) > 40){
                    $titulo_completo = substr($titulo_completo, 0, 40).'...';
                }

                $autores = $a->autoresHtml($mi_libro['autores']);
                
                $imagen=$mi_libro['imagen'];

                $html .= '
                <div class="card nuevo text-center mt-4 bg-white tarjeta_libro" data-label="NUEVO">
                    <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                        <a href="'.$config['build']['url'].'libro/'.$mi_libro['ruta'].'">
                            <img class="card-img" src="'.$config['build']['url'].$imagen.'">
                            '.$oferta.'
                        </a>
                    </div>
                    <span class="d-block mt-3 px-3">
                        <a class="text-truncate enlace_negro" href="'.$config['build']['url'].'libro/'.$mi_libro['ruta'].'">'.$titulo_completo.'</a>
                    </span>
                    <span class="d-block">
                        <p class="text-truncate m-0 px-3 autor">'.$autores.'</p>
                    </span>
                    <div class="row flex align-items-center my-2 precio">
                        '.$precio.'
                    </div>
                    <!--
                    <div class="card-footer px-3 py-2">
                        <div class="row d-flex align-items-center">
                            <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$mi_libro['id'].'">';
                                if($this->id_user === null){
                                    $html .= '<a href="'.$config['build']['url'].'autenticacion" class="love" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                }else{
                                    $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto={$mi_libro['id']}",1);
                                    if($stmt){
                                        $html .= '<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>';
                                    }else{
                                        $html .= '<a href="#" class="love agregar_deseo" id="'.$mi_libro['id'].'" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                    }
                                }
                            $html .= '
                            </div>
                            <div class="col-9 pl-1 text-right">';
                            if($mi_libro['stock'] > 0){
                                $html .= '
                                <button type="button" idLibro="'.$mi_libro['id'].'" class="btn btn-sm btn-icon boton_color agregar_carrito">
                                    <span class="btn-inner--text d-none d-sm-inline">Agregar</span>
                                    <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
                                </button>';
                            }
                            $html .= '    
                            </div>
                        </div>                                                                                                                 
                    </div>-->
                </div>';
            }
        }
        return $html;
    }

    public function slideMasVendidos(){
        $stmt = (new Model\Libros)->librosPor('*, SUM(ventasMostrador + ventas) as ventasTotales', null, "estado = 1", 20, 'GROUP BY id ORDER BY ventasTotales DESC');
        if($stmt){
            global $config;
            $html = '';
            $l = new Model\Libro;
            $a = new Model\Autor;

            $cont = 1;
            foreach($stmt as $key => $mi_libro){
                $posicion = '<span class="badge badge-lg badge-pill badge-posicion z-depth-1">TOP '.$cont.'</span>';

                $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                $precio = $libro_precio_oferta[0];
                $oferta = $libro_precio_oferta[1];
                
                $titulo_completo = $mi_libro['producto'].' '.$mi_libro['leyenda'];
                if(strlen($titulo_completo) > 40){
                    $titulo_completo = substr($titulo_completo, 0, 40).'...';
                }

                $autores = $a->autoresHtml($mi_libro['autores']);
                
                $intervalo = $l->libroNuevo($mi_libro);
                if($mi_libro['novedad'] == 1 && $intervalo <= 30){
                    $html .= '<div class="card nuevo text-center mt-4 bg-white tarjeta_libro" data-label="NUEVO">';   
                }else{
                    $html .= '<div class="card text-center mt-4 bg-white tarjeta_libro">';
                }
                
                $imagen=$mi_libro['imagen'];
                
                $html .= '
                    <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                        <a href="'.$config['build']['url'].'libro/'.$mi_libro['ruta'].'">
                            <img class="card-img" src="'.$config['build']['url'].$imagen.'">
                            '.$oferta.'
                            '.$posicion.'
                        </a>
                    </div>
                    <span class="d-block mt-3 px-3">
                        <a class="text-truncate enlace_negro" href="'.$config['build']['url'].'libro/'.$mi_libro['ruta'].'">'.$titulo_completo.'</a>
                    </span>
                    <span class="d-block">
                        <p class="text-truncate m-0 px-3 autor">'.$autores.'</p>
                    </span>
                    <div class="row flex align-items-center my-2 precio">
                        '.$precio.'
                    </div>
                    <!--
                    <div class="card-footer px-3 py-2">
                        <div class="row d-flex align-items-center">
                            <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$mi_libro['id'].'">';
                                if($this->id_user === null){
                                    $html .= '<a href="'.$config['build']['url'].'autenticacion" class="love" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                }else{
                                    $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto={$mi_libro['id']}",1);
                                    if($stmt){
                                        $html .= '<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>';
                                    }else{
                                        $html .= '<a href="#" class="love agregar_deseo" id="'.$mi_libro['id'].'" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                    }
                                }
                            $html .= '
                            </div>
                            <div class="col-9 pl-1 text-right">';
                            if($mi_libro['stock'] > 0){
                                $html .= '
                                <button type="button" idLibro="'.$mi_libro['id'].'" class="btn btn-sm btn-icon boton_color agregar_carrito">
                                    <span class="btn-inner--text d-none d-sm-inline">Agregar</span>
                                    <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
                                </button>';
                            }
                            $html .= '    
                            </div>
                        </div>                                                                                                                 
                    </div>
                    -->
                </div>';
                $cont++;
            }
        }
        return $html;
    }

    public function cargarNuevos(){

        global $http,$config;
        
        $orden = $http->query->get('orden');

        $where = "estado = 1 AND fechaRegistro BETWEEN CURDATE() - INTERVAL 30 DAY AND NOW()";

        $direccion = "ASC";

        if($orden == 'nombre' || $orden == 'nombre_desc'){
            $ordenarPor = 'producto';
            if($orden == 'nombre_desc'){
                $direccion = "DESC";
            }
        }elseif($orden == 'precio' || $orden == 'precio_desc'){
            $ordenarPor = 'precioMostrar';
            if($orden == 'precio_desc'){
                $direccion = "DESC";
            }
        }

        if($orden == 'novedad'){
            $ordenarPor = 'id';
            $direccion = "DESC";
        }

        if($orden == 'relevancia'){
            $ordenarPor = 'ventasTotales';
            $direccion = "DESC";
        }
        
        $this->db->query("DROP VIEW IF EXISTS novedades");
        $this->db->query("CREATE VIEW novedades AS 
                            SELECT *, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar, (ventasMostrador + ventas) as ventasTotales
                            FROM productos
                            WHERE estado = 1 AND novedad = 1 AND fechaRegistro BETWEEN CURDATE() - INTERVAL 30 DAY AND NOW()
                            ORDER BY id DESC 
                            LIMIT 50");
        $stmt_total = $this->db->select("*","novedades");
        $coincidencias = 0;
        $coincidencias_txt = '0 resultados para';

        $html = '';
        $l = new Model\Libro;
        $a = new Model\Autor;

        if($stmt_total){

            $coincidencias = count($stmt_total);
            $coincidencias_txt = $coincidencias.' resultado'.(($coincidencias == 1) ? '': 's').' para';

            $stmt_paginacion = $this->db->query("SELECT * FROM novedades ORDER BY $ordenarPor $direccion");
            if($stmt_paginacion){ 
                $cont = 1;
                foreach($stmt_paginacion as $key => $mi_libro){
                    $posicion = ($ordenarPor == 'ventasTotales') ? '<span class="badge badge-lg badge-pill badge-posicion z-depth-1" data-toggle="tooltip" data-html="true" title="<small>En Novedades<small>" style="cursor:help;">TOP. '.$cont.'</span>' : '';
                    
                    $nuevo = '<div class="card nuevo text-center mt-4 bg-white tarjeta_libro" data-label="NUEVO">';

                    $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                    $precio = $libro_precio_oferta[0];
                    $oferta = $libro_precio_oferta[1];
                    
                    $id_producto = $mi_libro['id'];
                    
                    $producto = $mi_libro['producto'].' '.$mi_libro['leyenda'];
                    /*if(strlen($producto) > 40){
                        $producto = substr($producto, 0, 40).'...';
                    }*/
                    
                    $autores = $a->autoresHtml($mi_libro['autores']);
                    $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                    $imagen = $config['build']['url'].$mi_libro['imagen'];
                    $stock = (int) $mi_libro['stock'];
                    $html .= (new Model\Libro)->libroHtml($ruta,$imagen,$nuevo,$oferta,$posicion,$producto,$autores,$precio,$id_producto,$stock);
                    $cont++;
                }

                return array('coincidencias' => $coincidencias_txt, 'html' => $html);
            }
        }

        return array('coincidencias' => $coincidencias_txt, 'html' => $html);

    }

    public function cargarMasvendidos(){
        global $http,$config;

        $array_mostrar = [25,50,75,100];
        $mostrar = intval($http->query->get('mostrar'));
        if(!in_array($mostrar, $array_mostrar)){
            $mostrar = 25;
        }
        $where = "estado = 1";

        $stmt_total = (new Model\Libros)->librosPor('*, SUM(ventasMostrador + ventas) as ventasTotales', null, $where, 100, "GROUP BY id ORDER BY ventasTotales DESC");

        $coincidencias = 0;
        $coincidencias_txt = '0 resultados para';

        $html = '';
        $l = new Model\Libro;
        $a = new Model\Autor;

        if($stmt_total){

            $coincidencias = count($stmt_total);
            $coincidencias_txt = $coincidencias.' resultado'.(($coincidencias == 1) ? '': 's').' para';

            $stmt_paginacion = $this->db->query("SELECT *, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar, SUM(ventasMostrador + ventas) as ventasTotales FROM productos WHERE $where GROUP BY id ORDER BY ventasTotales DESC LIMIT $mostrar");
            if($stmt_paginacion){ 
                $cont = 1;
                $resultados_pagina = $stmt_paginacion->num_rows;
                foreach($stmt_paginacion as $key => $mi_libro){
                    $posicion = '<span class="badge badge-lg badge-pill badge-posicion z-depth-1">TOP '.$cont.'</span>';

                    $intervalo = $l->libroNuevo($mi_libro);
                    if($mi_libro['novedad'] == 1 && $intervalo <= 30){
                        $nuevo = '<div class="card nuevo text-center mt-4 bg-white tarjeta_libro" data-label="NUEVO">';   
                    }else{
                        $nuevo = '<div class="card text-center mt-4 bg-white tarjeta_libro">';
                    }

                    $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                    $precio = $libro_precio_oferta[0];
                    $oferta = $libro_precio_oferta[1];
                    
                    $id_producto = $mi_libro['id'];
                    
                    $producto = $mi_libro['producto'].' '.$mi_libro['leyenda'];
                    /*if(strlen($producto) > 40){
                        $producto = substr($producto, 0, 40).'...';
                    }*/
                    
                    $autores = $a->autoresHtml($mi_libro['autores']);
                    $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                    $imagen = $config['build']['url'].$mi_libro['imagen'];
                    $stock = (int) $mi_libro['stock'];
                    $html .= (new Model\Libro)->libroHtml($ruta,$imagen,$nuevo,$oferta,$posicion,$producto,$autores,$precio,$id_producto,$stock);
                    $cont++;
                }

                return array('coincidencias' => $coincidencias_txt, 'html' => $html);
            }

        }

        return array('coincidencias' => $coincidencias_txt, 'html' => $html);

    }
    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}
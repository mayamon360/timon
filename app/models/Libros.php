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
 * Modelo Libros
 */
class Libros extends Models implements IModels {
    use DBModel;

    public function librosPor($fields = '*', $inners = null, $where = null, $limit = null, $extra = '', $tabla = 'productos'){
        $stmt = $this->db->select($fields, $tabla, $inners, $where, $limit, $extra);
        return $stmt;
    }

    public function librosSlideEditorial($id_editorial, int $libro_excluido){
        $stmt = $this->librosPor('*', null, "estado = 1 AND id_editorial = $id_editorial AND id != $libro_excluido", 20, 'ORDER BY RAND()');
        $html = '';
        if($stmt){
            global $config;
            $html = '<div class="owl-carousel carrusel_libros_editorial owl-theme">';
            $l = new Model\Libro;
            $a = new Model\Autor;

            foreach($stmt as $key => $mi_libro){
                
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
                if(strlen($producto) > 40){
                    $producto = substr($producto, 0, 40).'...';
                }
                    
                $autores = $a->autoresHtml($mi_libro['autores']);
                $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                $imagen = $config['build']['url'].$mi_libro['imagen'];
                $stock = (int) $mi_libro['stock'];
                $html .= (new Model\Libro)->slideLibroHtml($ruta,$imagen,$nuevo,$oferta,$producto,$autores,$precio,$id_producto,$stock);

            }

            $html .= '</div>';
        }
        return $html;
    }

    public function librosSlideCategoria($id_categoria, int $libro_excluido){
        $stmt = $this->librosPor('*', null, "estado = 1 AND idCategoria = $id_categoria AND id != $libro_excluido", 20, 'ORDER BY RAND()');
        $html = '';
        if($stmt){
            global $config;
            $html = '<div class="owl-carousel carrusel_libros_categoria owl-theme">';
            $l = new Model\Libro;
            $a = new Model\Autor;

            foreach($stmt as $key => $mi_libro){

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
                if(strlen($producto) > 40){
                    $producto = substr($producto, 0, 40).'...';
                }
                    
                $autores = $a->autoresHtml($mi_libro['autores']);
                $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                $imagen = $config['build']['url'].$mi_libro['imagen'];
                $stock = (int) $mi_libro['stock'];
                $html .= (new Model\Libro)->slideLibroHtml($ruta,$imagen,$nuevo,$oferta,$producto,$autores,$precio,$id_producto,$stock);

            }

            $html .= '</div>';
        }
        return $html;
    }

    public function librosSlideSubcategoria($id_subcategoria, int $libro_excluido){
        $stmt = $this->librosPor('*', null, "estado = 1 AND idSubcategoria = $id_subcategoria AND id != $libro_excluido", 20, 'ORDER BY RAND()');
        $html = '';
        if($stmt){
            global $config;
            $html = '<div class="owl-carousel carrusel_libros_subcategoria owl-theme">';
            $l = new Model\Libro;
            $a = new Model\Autor;

            foreach($stmt as $key => $mi_libro){

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
                if(strlen($producto) > 40){
                    $producto = substr($producto, 0, 40).'...';
                }
                    
                $autores = $a->autoresHtml($mi_libro['autores']);
                $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                $imagen = $config['build']['url'].$mi_libro['imagen'];
                $stock = (int) $mi_libro['stock'];
                $html .= (new Model\Libro)->slideLibroHtml($ruta,$imagen,$nuevo,$oferta,$producto,$autores,$precio,$id_producto,$stock);

            }

            $html .= '</div>';
        }
        return $html;
    }

    public function cargarLibros(){
        global $http,$config;

        $orden = $http->query->get('orden');
        $pagina = intval($http->query->get('pagina'));
        $metodo = $this->db->scape($http->query->get('metodo'));
        $id = intval($http->query->get('id'));
        
        $categoria = (new Model\Datos)->categoriaPor("id='$id' AND ruta = '$metodo'");
        $subcategoria = (new Model\Datos)->subcategoriaPor("id='$id' AND ruta = '$metodo'");

        //$libros_en = ($categoria) ? $categoria['categoria'] : $subcategoria['subcategoria'];
        $where = ($categoria) ? "idCategoria = '{$categoria['id']}'" : "idSubcategoria = '{$subcategoria['id']}'";

        $limite = 25;
        $posicion = (($pagina-1) * $limite);

        if($pagina == 0){
            $pagina = 1;
        }
        
        if($pagina == 1){
            $cont = 1;
        }else{
            $cont = $posicion + 1;
        }
        
        $ordenarPor = 'ventasTotales';
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

        if($ordenarPor == 'ventasTotales'){
            $direccion = "DESC";
        }

        $stmt_total = (new Model\Libros)->librosPor('*', null, $where);
        $coincidencias = 0;
        $coincidencias_txt = '0 resultados para';
        $paginas = 0;
        $ultima_pagina = true;

        $html = '';
        $l = new Model\Libro;
        $a = new Model\Autor;
        
        if($stmt_total){

            $coincidencias = count($stmt_total);
            $coincidencias_txt = $coincidencias.' resultado'.(($coincidencias == 1) ? '': 's').' para';
            $paginas = ceil($coincidencias / $limite);
            if($pagina != $paginas){
                $ultima_pagina = false;
            }

            $stmt_paginacion = $this->db->query("SELECT *, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar, SUM(ventasMostrador + ventas) as ventasTotales FROM productos WHERE $where GROUP BY id ORDER BY $ordenarPor $direccion LIMIT $posicion, $limite");
            if($stmt_paginacion){
                $resultados_pagina = $stmt_paginacion->num_rows;
                foreach($stmt_paginacion as $key => $mi_libro){
                    $posicion = ($ordenarPor == 'ventasTotales') ? '<span class="badge badge-lg badge-pill badge-posicion z-depth-1" data-toggle="tooltip" data-html="true" title="<small>De '.$libros_en.'<small>" style="cursor:help;">No. '.$cont.' <i class="fas fa-question-circle"></i></span>' : '';
                    
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
                    if(strlen($producto) > 40){
                        $producto = substr($producto, 0, 40).'...';
                    }
                    
                    $autores = $a->autoresHtml($mi_libro['autores']);
                    $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                    $imagen = $config['build']['url'].$mi_libro['imagen'];
                    $stock = $mi_libro['stock'];
                    $html .= (new Model\Libro)->libroHtml($ruta,$imagen,$nuevo,$oferta,$posicion,$producto,$autores,$precio,$id_producto,$stock);
                    $cont++;
                }

                return array('coincidencias' => $coincidencias_txt, 'ultima_pagina' => $ultima_pagina, 'total_resultados' => $coincidencias, 'resultados_pagina' => $resultados_pagina, 'html' => $html);
            }

        }

        return array('coincidencias' => $coincidencias_txt, 'ultima_pagina' => $ultima_pagina, 'total_resultados' => $coincidencias, 'resultados_pagina' => 0, 'html' => $html);

    }

    public function cargarDeseos(){
        global $http,$config;

        $orden = $http->query->get('orden');
        $pagina = intval($http->query->get('pagina'));
    
        $where = "productos.estado = 1 AND deseos.idUsuario={$this->id_user}";

        $limite = 25;
        $posicion = (($pagina-1) * $limite);

        if($pagina == 0){
            $pagina = 1;
        }
        
        if($pagina == 1){
            $cont = 1;
        }else{
            $cont = $posicion + 1;
        }

        $ordenarPor = 'productos.producto';
        $direccion = "ASC";

        if($orden == 'nombre_desc'){
            if($orden == 'nombre_desc'){
                $direccion = "DESC";
            }
        }elseif($orden == 'precio' || $orden == 'precio_desc'){
            $ordenarPor = 'precioMostrar';
            if($orden == 'precio_desc'){
                $direccion = "DESC";
            }
        }

        $stmt_total = (new Model\Libros)->librosPor('productos.*', "LEFT JOIN deseos ON productos.id=deseos.idProducto", $where);
        $coincidencias = 0;
        $coincidencias_txt = '0 libros en tu';
        $paginas = 0;
        $ultima_pagina = true;

        $html = '';
        $l = new Model\Libro;
        $a = new Model\Autor;
        
        if($stmt_total){

            $coincidencias = count($stmt_total);
            $coincidencias_txt = $coincidencias.' libro'.(($coincidencias == 1) ? '': 's').' en tu';
            $paginas = ceil($coincidencias / $limite);
            if($pagina != $paginas){
                $ultima_pagina = false;
            }

            $stmt_paginacion = $this->db->query("SELECT productos.*, IF(productos.precioOferta > 0 ,productos.precioOferta, productos.precio) AS precioMostrar, SUM(productos.ventasMostrador + productos.ventas) as ventasTotales FROM productos LEFT JOIN deseos ON productos.id=deseos.idProducto WHERE $where GROUP BY productos.id ORDER BY $ordenarPor $direccion LIMIT $posicion, $limite");
            if($stmt_paginacion){
                $resultados_pagina = $stmt_paginacion->num_rows;
                foreach($stmt_paginacion as $key => $mi_libro){
                    
                    $intervalo = $l->libroNuevo($mi_libro);
                    if($mi_libro['novedad'] == 1){
                        $nuevo = '<div class="card nuevo text-center mt-4 bg-white tarjeta_libro" data-label="NUEVO">';   
                    }else{
                        $nuevo = '<div class="card text-center mt-4 bg-white tarjeta_libro">';
                    }

                    $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                    $precio = $libro_precio_oferta[0];
                    $oferta = $libro_precio_oferta[1];
                    
                    $id_producto = $mi_libro['id'];
                    
                    $producto = $mi_libro['producto'].' '.$mi_libro['leyenda'];
                    if(strlen($producto) > 40){
                        $producto = substr($producto, 0, 40).'...';
                    }
                    
                    $autores = $a->autoresHtml($mi_libro['autores']);
                    $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
                    $imagen = $config['build']['url'].$mi_libro['imagen'];
                    $stock = (int) $mi_libro['stock'];
                    $html .= (new Model\Libro)->deseoLibroHtml($ruta,$imagen,$nuevo,$oferta,$producto,$autores,$precio,$id_producto,$stock);
        
                }

                return array('coincidencias' => $coincidencias_txt, 'ultima_pagina' => $ultima_pagina, 'total_resultados' => $coincidencias, 'resultados_pagina' => $resultados_pagina, 'html' => $html);
            }

        }

        return array('coincidencias' => $coincidencias_txt, 'ultima_pagina' => $ultima_pagina, 'total_resultados' => $coincidencias, 'resultados_pagina' => 0, 'html' => $html);

    }


    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}
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
 * Modelo Autor
 */
class Autor extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene datos de un autor según condiciones en la base de datos
     *
     * @param int $id: Id del autor a obtener
     * 
     * @return false|array[0]
    */  
    public function autorPor($where) {
        $stmt = $this->db->select("*", "autores", null, $where, 1);
        if($stmt){
            return $stmt[0];
        }else{
            return false;
        } 
    }

    public function autoresHtml($id_autores, $como_enlace = false){
        global $config;
        $array_autores = explode(",", $id_autores);
        $autores = "";
        foreach($array_autores as $key1 => $value2){
            $autor = $this->autorPor("id_autor = $value2");
            if($autor){
                if($como_enlace){
                    $autores .= '<a class="d-block text-truncate pixelAutor" href="'.$config['build']['url'].'autor/'.$autor['ruta'].'">
                        '.$autor['autor'].'
                    </a>';
                }else{
                    $autores .= $autor['autor'].' ';
                }
            }else{
                if($como_enlace){
                    $autores .= '<a class="text-truncate pixelAutor" href="'.$config['build']['url'].'autor/sin-autor">
                        Sin autor
                    </a>, ';
                }else{
                    $autores .= 'Sin autor';
                }
            }
        }
        return $autores; 
    }

    public function cargarLibrosAutor(){
        global $http,$config;

        $orden = $http->query->get('orden');
        $pagina = intval($http->query->get('pagina'));
        $metodo = $this->db->scape($http->query->get('metodo'));

        $autor = $this->autorPor("estado = 1 AND ruta = '$metodo'");

        $where = "estado = 1 AND FIND_IN_SET({$autor['id_autor']},autores)>0";

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
                    $posicion = ($ordenarPor == 'ventasTotales') ? '<span class="badge badge-lg badge-pill badge-posicion z-depth-1" data-toggle="tooltip" data-html="true" title="<small>De '.$autor['autor'].'<small>" style="cursor:help;">No. '.$cont.' <i class="fas fa-question-circle"></i></span>' : '';

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
                    $html .= (new Model\Libro)->libroHtml($ruta,$imagen,$nuevo,$oferta,$posicion,$producto,$autores,$precio,$id_producto,$stock);
                    $cont++;
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
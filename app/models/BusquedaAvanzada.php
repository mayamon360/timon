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
 * Modelo Busqueda
 */
class BusquedaAvanzada extends Models implements IModels {
    use DBModel;

    public function realizarBusquedAvanzada(){
        global $http,$config;

        $orden = $http->query->get('orden');
        $pagina = intval($http->query->get('pagina'));
        $isbn = $this->db->scape($http->query->get('isbn'));
        $title = $this->db->scape($http->query->get('title'));
        $author = $this->db->scape($http->query->get('author'));
        $publishing = $this->db->scape($http->query->get('publishing'));
        $category = $this->db->scape($http->query->get('category'));
        
        $where = "p.estado = 1 AND (p.codigo LIKE '%$isbn%'";
        
        if ($title != "") {
            $where .= " AND (p.producto LIKE '%$title%' OR p.leyenda LIKE '%$title%')";
        }
        if ($author != "") {
            $where .= " AND a.autor LIKE '%$author%'";
        }
        if ($publishing != "") {
            $where .= "AND e.editorial LIKE '%$publishing%'";
        }
        if ($category != "") {
            $where .= " AND (ct.categoria LIKE '%$category%' OR sb.subcategoria LIKE '%$category%')";
        }
        
        $where .= ")";

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

        $direccion = "ASC";

        if($orden == 'nombre' || $orden == 'nombre_desc'){
            $ordenarPor = 'p.producto';
            if($orden == 'nombre_desc'){
                $direccion = "DESC";
            }
        }elseif($orden == 'precio' || $orden == 'precio_desc'){
            $ordenarPor = 'precioMostrar';
            if($orden == 'precio_desc'){
                $direccion = "DESC";
            }
        }

        if($orden == 'relevancia'){
            $ordenarPor = 'ventasTotales';
            $direccion = "DESC";
        }

        $stmt_total = (new Model\Libros)->librosPor('p.*', " JOIN editoriales AS e ON p.id_editorial=e.id_editorial JOIN productos_autores pa ON pa.id_producto = p.id
        JOIN autores a ON a.id_autor = pa.id_autor JOIN categorias AS ct ON p.idCategoria = ct.id JOIN subcategorias AS sb ON p.idSubcategoria = sb.id", $where, null, 'group by p.id', 'productos AS p');
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

            $stmt_paginacion = $this->db->query("SELECT p.*, IF(p.precioOferta > 0 ,p.precioOferta, p.precio) AS precioMostrar, (p.ventasMostrador + p.ventas) AS ventasTotales 
            FROM productos AS p 
            JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
            JOIN productos_autores pa ON pa.id_producto = p.id
            JOIN autores a ON a.id_autor = pa.id_autor
            JOIN categorias AS ct ON p.idCategoria = ct.id 
            JOIN subcategorias AS sb ON p.idSubcategoria = sb.id
            WHERE $where GROUP BY p.id ORDER BY $ordenarPor $direccion LIMIT $posicion, $limite");
            if($stmt_paginacion){
                $resultados_pagina = $stmt_paginacion->num_rows;
                foreach($stmt_paginacion as $key => $mi_libro){
                    $posicion = ($ordenarPor == 'ventasTotales') ? '<span class="badge badge-lg badge-pill badge-posicion z-depth-1" data-toggle="tooltip" data-html="true" title="<small>Posición en búsqueda<small>" style="cursor:help;">TOP '.$cont.' <i class="fas fa-question-circle"></i></span>' : '';
                    
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

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}
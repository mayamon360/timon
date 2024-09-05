<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador libro/
*/
class libroController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        $ruta = $router->getMethod();

        if($ruta === null || $ruta == 'null'){
            Helper\Functions::redir();
        }else{
            $l = new Model\Libro;
            $ls = new Model\Libros;
            $a = new Model\Autor;
            $e = new Model\Editorial;
            $d = new Model\Datos;
            $mi_libro = $l->libroPor('*', null, "ruta = '$ruta'", 1, '');
            if(!$mi_libro){
                Helper\Functions::redir();
            }else{
                $categoria_enlace = $d->categoriasHtml($mi_libro['idCategoria'], true);
                $subcategoria_enlace = $d->subcategoriasHtml($mi_libro['idCategoria'], $mi_libro['idSubcategoria'], true);

                $autores_html = $a->autoresHtml($mi_libro['autores'], true);
                $editorial = $e->editorialPor("id_editorial = ".$mi_libro['id_editorial']."");

                $intervalo = $l->libroNuevo($mi_libro);
                $nuevo = ($mi_libro["novedad"] == 1 && $intervalo <= 30) ? 'si': 'no';
                
                $descripcion = nl2br($mi_libro['descripcion']);

                $libro_precio_oferta = $l->libroPrecioOferta($mi_libro);
                $oferta = $libro_precio_oferta[1];
                $precio = $libro_precio_oferta[2];

                $puntos = ((real) $mi_libro['precio'] * 5)/100;

                $disponibilidad = $l->disponibilidad($mi_libro['stock']);
                
                if($mi_libro['detalles'] != ''){
                    $detalles = $l->detalles($mi_libro['detalles']);	
                }else{
                    $detalles = [0 => '',1 => ''];
                }

                $libros_editorial = $ls->librosSlideEditorial($mi_libro['id_editorial'], $mi_libro['id']);
                $libros_categoria = $ls->librosSlideCategoria($mi_libro['idCategoria'], $mi_libro['id']);
                $libros_subcategoria = $ls->librosSlideSubcategoria($mi_libro['idSubcategoria'], $mi_libro['id']);

                $deseo = ($l->validarDeseo($mi_libro['id'])) ? 'si' : 'no';

                $this->template->display('libro/libro', array(
                    'categoria_enlace' => $categoria_enlace,
                    'subcategoria_enlace' => $subcategoria_enlace,
                    'libro' => $mi_libro, 
                    'autores_html' => $autores_html, 
                    'editorial' => $editorial['editorial'],
                    'ruta_editorial' => $editorial['ruta'],
                    'nuevo' => $nuevo,
                    'oferta' => $oferta,
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                    'puntos' => $puntos,
                    'disponibilidad' => $disponibilidad,
                    'tr_detalles' => $detalles[0],
                    'td_detalles' => $detalles[1],
                    'categoria' => $d->categoriasHtml($mi_libro['idCategoria']),
                    'subcategoria' => $d->subcategoriasHtml($mi_libro['idCategoria'], $mi_libro['idSubcategoria']),
                    'libros_editorial' => $libros_editorial,
                    'libros_categoria' => $libros_categoria,
                    'libros_subcategoria' => $libros_subcategoria,
                    'deseo' => $deseo)
                );
            }
        }

    }
}
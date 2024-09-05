<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador libros/
*/
class librosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        $metodo = $router->getMethod();
        $id = $router->getId();
        $categoria = (new Model\Datos)->categoriaPor("estado = 1 AND ruta = '$metodo'");
        $subcategoria = (new Model\Datos)->subcategoriaPor("estado = 1 AND ruta = '$metodo'");

        if($categoria || $subcategoria){
            $seccion = ($categoria) ? $categoria['categoria'] : $subcategoria['subcategoria'];
            $this->template->display('libros/libros', array(
                'metodo' => $metodo,
                'id' => $id,
                'seccion' => $seccion
            ));
        }else{
            Helper\Functions::redir();
        }

    }
}
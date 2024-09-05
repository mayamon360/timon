<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador administradores/
*/
class administradoresController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        global $config;
        
        # Método y id desde la ruta
        $metodo = $router->getMethod();
        $id = $router->getId(true);

        # Llamar al modelo Administradores
        $a = new Model\Administradores;
        
        # Llamar al modelo Perfiles
        $p = new Model\Perfiles;

        # Llamar al modelo Almacen
        $al = new Model\Almacenes;

        # Agregar funciones del helper Strings
        $this->template->addExtension(new Helper\Strings);

        # Si el método es 'perfil' y el id diferente de null
        if($metodo == 'perfil' && $id != null){

            # Obtener los datos del administrador según el id 
            $perfil = $a->administrador($id);

            # Si el administrador existe
            if($perfil){

                # Renderizar perfil, agregando los datos del usuario
                $this->template->display('administradores/perfil', array(
                    'datosPerfil' => $perfil
                ));

            }else{

                # Redireccionar a administradores
                Helper\Functions::redir($config['build']['url'].'administradores');

            }

        }else{

            # Renderizar administardores
        	$this->template->display('administradores/administradores', array(
                'perfiles' => $p->perfiles(),
                'almacenes' => $al->almacenes(),
                'datosModulo' => $datosModulo
            ));

        }
    
    }

}
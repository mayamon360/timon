<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador busqueda/
*/
class busquedAvanzadaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        global $http;

        $s_search = $http->query->get('s_search');
        $isbn = $http->query->get('s_isbn');
        $title = $http->query->get('s_title');
        $author = $http->query->get('s_author');
        $publishing = $http->query->get('s_publishing');
        $category = $http->query->get('s_category');
        
        if($s_search == null || $s_search == '' || Helper\Functions::emp($s_search)){
            $this->template->display('busquedAvanzada/formulario');
        }else{
            if ( ( $isbn != null || $isbn != '' || !Helper\Functions::emp($isbn) )
            || ( $title != null || $title != '' || !Helper\Functions::emp($title) )
            || ( $author != null || $author != '' || !Helper\Functions::emp($author) )
            || ( $publishing != null || $publishing != '' || !Helper\Functions::emp($publishing) )
            || ( $category != null || $category != '' || !Helper\Functions::emp($category) ) ) {
                
                $this->template->display('busquedAvanzada/resultados', array(
                    'isbn' => $isbn,
                    'title' => $title,
                    'author' => $author,
                    'publishing' => $publishing,
                    'category' => $category
                ));
                
            } else {
                Helper\Functions::redir("/busquedAvanzada");
            }
        }	
        
    }
}
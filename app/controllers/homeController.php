<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

class homeController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        $h = new Model\Home;
        $slides = $h->slides();

        $d = new Model\Destacados;
        $stephenKing = $d->autores(109);
        $pauloCoelho = $d->autores(116);
        $carlosFuentes = $d->autores(181);
        $alexHirsch = $d->autores(2606);
        $danBrown = $d->autores(455);
        $novedades = $d->slideNovedades();
        $mas_vendidos = $d->slideMasVendidos();

        $this->template->display('home/home', array(
            'stephen' => $stephenKing,
            'paulo' => $pauloCoelho,
            'carlos' => $carlosFuentes,
            'alex' => $alexHirsch,
            'dan' => $danBrown,
            'slides' => $slides, 
            'novedades' => $novedades, 
            'mas_vendidos' => $mas_vendidos)
        );
    }
}
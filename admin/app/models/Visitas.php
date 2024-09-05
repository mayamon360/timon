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
 * Modelo Vistas
 */
class Visitas extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todas las visitas
     * 
     * @return false|array
    */ 
    public function visitas() {
       $ventas = $this->db->select("*", "visitas", null, null, null, "ORDER BY id DESC");
        return $ventas;
    }

    /**
     * Obtiene las visitas agrupadas por región de México
     *    
     *
     * @return false|string con el total
     */ 
    public function visitasMX() {

        $visitasM = $this->db->select('*', 'visitasregion', null, "codigoPais='MX'", null, "ORDER BY cantidad ASC");
        return $visitasM;

    }

    /**
     * Obtiene la suma total de la visitas en México
     *    
     *
     * @return false|string con el total
     */ 
    public function totalVisitasMX() {

        $totalVisitasM = $this->db->select('SUM(cantidad) AS total', 'visitasregion', null, "codigoPais='MX'");
        return $totalVisitasM[0]["total"];

    }

    /**
     * Retorna los datos de las categorías para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarVisitas() : array {

        $visitas = $this->visitas();
        $data = [];

        if($visitas){
            foreach ($visitas as $key => $value) {
                $infoData = [];

                $infoData[] = $value["region"];
                $infoData[] = $value["ciudad"];
                $infoData[] = $value["ip"];
                $infoData[] = $value["visitas"];
                $infoData[] = Helper\Functions::fecha($value["fecha"]);
                $infoData[] = $value["pais"];

                $data[] = $infoData;
            }
        }

        $json_data = [
            "data"   => $data   
        ];
        return $json_data;

    }

    public function reporteVisitas() {
        $nombre = "Reporte de visitas.xls"; 
        $visitas = $this->visitas();

        if($visitas){
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel'); //Archivo excelS
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');
            $html = "";
            $html .= "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>PAÍS</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REGIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>CIUDAD</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>IP PÚBLICA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>VISITAS</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FECHA</td>
                    </tr>";
            $cont = 1;
            foreach ($visitas as $key => $value) {

                $backColor = ($cont%2==0) ? 'background-color:#f7f7f7;' : 'background-color:#fff;'; 

                $html .= "
                    <tr>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['pais']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['region']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['ciudad']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['ip']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['visitas']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['fecha']."</td>
                    </tr>
                ";

                $cont++;
            }

            $html .= '</table>';
            echo utf8_decode($html);
        }  

    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}
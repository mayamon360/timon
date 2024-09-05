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
 * Modelo Pruebas
 */
class Pruebas extends Models implements IModels {
    use DBModel;
    
    public function mostrarPruebas() : array {
    
    	global $config;
    
        # Obtener la url de multimedia
        $servidor = $config['build']['urlAssets'];
        $paginaWeb = $config['build']['urlAssetsPagina'];
        
    	$productos = $this->db->query("SELECT p.id, p.imagen, p.codigo, p.producto, a.autor, e.editorial, p.tipo, p.stock, p.stock_minimo, p.precio, (p.stock * p.precio) AS monto, p.estado, (p.ventasMostrador + p.ventas) AS ventas FROM productos AS p INNER JOIN autores AS a ON FIND_IN_SET(a.id_autor, p.autores) > 0 INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial GROUP BY p.id");

    	$data = [];
    	if($productos){
	    	foreach ($productos as $key => $value) {
	    	
	    	    $infoData = [];
	    	    
	    	    $precio = (real) $value['precio'];
	    	    
	    	    $infoData[] = $value["id"];
	            $infoData[] = $value["codigo"];
	            $infoData[] = $value["producto"];
	            $infoData[] = $value["autor"];
	            $infoData[] = $value["editorial"];
	            
	            if($value["tipo"] == 'fisico'){

                        $stock_minimo_medio = $value['stock_minimo']/2;
                    	$stock_minimo = $value['stock_minimo'];
                    	$stock_medio = $stock_minimo + $stock_minimo_medio;

                    	if($value['stock'] >= 0 && $value['stock'] <= $stock_minimo_medio){
                           $infoData[] = "<span class='label bg-red animated infinite flash'>".$value['stock']."</span>";
                    	}elseif($value['stock'] > $stock_minimo_medio && $value['stock'] <= $stock_minimo){
                            $infoData[] = "<span class='label bg-red'>".$value['stock']."</span>";
                    	}elseif($value['stock'] > $stock_minimo && $value['stock'] <= $stock_medio){
                            $infoData[] = "<span class='label bg-yellow'>".$value['stock']."</span>";
                    	}elseif($value['stock'] > $stock_medio){
                            $infoData[] = "<span class='label bg-green'>".$value['stock']."</span>";
                    	}

                    }else{
                        $infoData[] = "--";
                    }
	    	     
                    
                    $infoData[] = '$ '.number_format($value["precio"],2); 
                    
                    $infoData[] = '$ '.number_format($value['monto'],2);
                    
                    $infoData[] = $value['ventas'];
                    
                    
                
	    	    $data[] = $infoData; 
	    	}
	    }
    	
    	$json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }
    
    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
    
}
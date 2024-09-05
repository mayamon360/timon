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
 * Modelo Autores
 */
 class Autores extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */

    const URL_ASSETS_WEBSITE = '../../';
    
    /**
     * Obtiene todas los autores
     * 
     * @return false|array
    */ 
    public function autores() {
        $stmt = $this->db->select('*', 'autores', null, null, null, "ORDER BY id_autor DESC");
        return $stmt;
    }
    
    /**
     * Obtiene datos de un autor según su id en la base de datos
     * 
     * @param int $id: Id del autor a obtener
     *
     * @return false|array[0]
    */ 
    public function autor($id) {
        $stmt = $this->db->select("*", 'autores', null, "id_autor='$id'", 1);
        if($stmt){
            return $stmt[0];
        }else{
            return false;
        }
    }
    
    /**
     * Obtiene los autores según el valor de un item
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array[0]
    */ 
    public function autoresPor($item, $valor){
        $autores = $this->db->select('*', 'autores', null, "$item='$valor'", null, 'ORDER BY id_autor DESC');
        if($autores){
            return $autores;
        }else{
            return false;
        }
    }
    
    /**
     * Retorna los datos de los autores para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarAutores() : array {
        global $config;
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }
        
        # Obtener la url de multimedia
        $paginaWeb = $config['build']['urlAssetsPagina'];
        
        $autores = $this->db->query("SELECT a.*, COUNT(p.id) as productos 
            FROM autores AS a 
            INNER JOIN productos_autores AS pa ON a.id_autor=pa.id_autor 
            INNER JOIN productos AS p ON pa.id_producto=p.id
            GROUP BY a.id_autor 
            ORDER BY a.id_autor DESC");
        
        $data = [];
        if($autores){
            foreach ($autores as $key => $value) {
                if($value["id_autor"] != 1){
                    
                    $infoData = [];
                    
                    $infoData[] = '<span class="badge bg-black" style="font-weight:800;">'.$value["id_autor"].'</span>';
                    $infoData[] = '<a href="'.$paginaWeb.'autor/'.$value["ruta"].'" target="_blank" data-toggle="tooltip" title="Abrir enlace"><strong>'.$value["autor"].'</strong></a>';
                    $infoData[] = $value["productos"];
                    
                    if($value["oferta"] != 0){
                        if($value["precioOferta"] != 0){
                            $infoData[] = '$'.$value["precioOferta"];
                            $infoData[] = $value["finOferta"];
                        }elseif($value["descuentoOferta"] != 0){
                            $infoData[] = $value["descuentoOferta"].'%';
                            $infoData[] = $value["finOferta"];
                        }
                    }else{
                        $infoData[] = "--";
                        $infoData[] = "--";
                    }

                    $infoData[] = "<div class='btn-group'>
                		<button class='btn btn-sm btn-default obtenerAutor' key='".$value["id_autor"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt' data-toggle='tooltip' title='Editar'></i></button>
                    	<button class='btn btn-sm btn-default eliminar' key='".$value["id_autor"]."'><i class='fas fa-trash-alt' data-toggle='tooltip' title='Eliminar'></i></button>
                    </div>";

                    $data[] = $infoData;    
                }
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }
    
    /**
     * Cambia el estado de un autor, afectando a productos
     *
     * 0:desactivado | 1:activado 
     *
     * @return array
    */ 
    public function estadoAutor() : array {
        try {
            throw new ModelsException('No disponible por el momento.');
            /*global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $autor= $this->autor($id);
            if(!$autor){
                throw new ModelsException('El autor no existe.');
            }else{
                $this->db->update('autores',array('estado' => $estado),"id_autor ='$id'",1);
                $this->db->update('productos',array('estado' => $estado),"FIND_IN_SET({$id},autores)>0");
            }

            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador){
                $administrador = $administrador;
                $perfiles = $administrador['perfiles'];
                foreach ($perfiles as $key => $value) {
                    if($value['principal']){
                        $perfil = $value['id_perfil'];
                    }
                }
            }

            if($estado == 1){
                $estado_txt = 'activo';
            }else{
                $estado_txt = 'inactivo';
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado de autor '.$id.' '.$autor['autor'].' a '.$estado_txt, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');*/
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Agrega una nuevo autor
     * 
     * @return array
    */ 
    public function agregarAutores() : array {
        try {
            global $http;
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            # Obtener los datos $_POST

            $nombreA = $this->db->scape( trim($http->request->get('nombreA') ) ) ;
            if($nombreA == ""){
                throw new ModelsException('El campo Autor está vació.');
            }
            $nombreA = Helper\Strings::clean_string(mb_strtoupper($nombreA, 'UTF-8'));
            $autor_existe = $this->db->select('*', 'autores', null, "autor = '".$nombreA."'");
            if($autor_existe){
                throw new ModelsException('El autor '.$nombreA.' ya existe en la base de datos.');
            }
            
            $rutaA = Helper\Strings::url_amigable(mb_strtolower($nombreA, 'UTF-8'));

            # Registrar autor
            $insertar = $this->db->insert('autores', array(
                'autor' => $nombreA,
                'ruta' => $rutaA,
                'oferta' => 0,
                'precioOferta' => '',
                'descuentoOferta' => '',
                'finOferta' => '',
                'estado' => 1
            ));

            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador){
                $administrador = $administrador;
                $perfiles = $administrador['perfiles'];
                foreach ($perfiles as $key => $value) {
                    if($value['principal']){
                        $perfil = $value['id_perfil'];
                    }
                }
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de autor '.$insertar.' '.$nombreA, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El autor '.$nombreA.' se agregó correctamente.', 'agregado' => $insertar);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Obtiene los datos de un autor para cargarlo en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerAutor() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http, $config;

            $id = intval($http->request->get('id'));

            if($id != "" && $id != 0){
                
                $autor = $this->autor($id);
                
                if($autor){

                    $productos = $this->db->select("*", "productos AS p", "INNER JOIN productos_autores AS pa ON p.id=pa.id_producto", "pa.id_autor = '$id'", null, "GROUP BY p.id");

                    if($productos){
                        
                        if($autor["oferta"] == 0){
                            
                            $options = '<option value="0" selected>Sin oferta</option><option value="1">Con oferta</option>';
                            $clase = 'hidden';
                            $oPrecio = "";
                            $pDisabled = "";
                            $oDescuento = "";
                            $dDisabled = "";
                            $oFecha = "";

                        }else{
                            
                            $options = '<option value="0">Sin oferta</option><option value="1" selected>Con oferta</option>';
                            $clase = 'show';

                            if($autor["descuentoOferta"] != 0){
                                
                                $oPrecio = "";
                                $pDisabled = "disabled";
                                $oDescuento = $autor["descuentoOferta"];
                                $dDisabled = "";
                                
                            }else{
                                
                                $oPrecio = $autor["precioOferta"];
                                $pDisabled = "";
                                $oDescuento = "";
                                $dDisabled = "disabled";
                                
                            }
                            
                            $oFecha = substr($autor["finOferta"], 0, -9);
                        }
                        
                        $informacion = count($productos).' productos de este autor.';
                        $nameSelect = 'eOferta';
                        
                    }else{
                        
                        $options = '<option disabled selected>La oferta no aplica</option>';
                        $clase = 'hidden';
                        $oPrecio = "";
                        $pDisabled = "";
                        $oDescuento = "";
                        $dDisabled = "";
                        $oFecha = "";
                        $informacion = '0 productos de este autor.';
                        $nameSelect = '';
                        
                    }

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="idA" id="idA" value="'.$autor["id_autor"].'">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon text-red">
                                                        <i class="fas fa-user-tie"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-uppercase eValidarA" name="eNombreA" id="eNombreA" value="'.$autor["autor"].'" placeholder="Autor" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <hr>
                                            <h4 class="text-center">OFERTA EXCLUSIVA EN LÍNEA</h4>
                                        </div> 
                                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                                            <p class="help-block text-center">'.$informacion.'</p>
                                            <div class="form-group text-center">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-certificate text-yellow"></i>
                                                    </span>
                                                    <select class="form-control" name="'.$nameSelect.'" id="'.$nameSelect.'">
                                                    '.$options.'
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="eOfertaDetalles '.$clase.'">
                                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <p class="help-block">Todo a:</p>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-dollar"></i>
                                                                </span>
                                                                <input type="text" class="form-control" id="eoPrecio" name="eoPrecio" value="'.$oPrecio.'" placeholder="Precio" '.$pDisabled.'>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <p class="help-block">Menos el:</p>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="eoPorcentaje" name="eoPorcentaje" value="'.$oDescuento.'" placeholder="Porcentaje" '.$dDisabled.'>
                                                                <span class="input-group-addon">
                                                                    <i class="fas fa-percent"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <p class="help-block">Vigencia:</p>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control datepicker" id="eoFecha" name="eoFecha" value="'.$oFecha.'" placeholder="Fin de la oferta">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>';

                    return array('status' => 'success', 'formulario' => $formulario);
                }else{
                    throw new ModelsException('El autor no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
            }    
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Edita un autor
     * 
     * @return array
    */ 
    public function editarAutor() : array {
        try {
            global $http;
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            $id = intval($http->request->get("idA"));

            $nombreA = $this->db->scape( trim($http->request->get('eNombreA') ) );
            if($nombreA == ""){
                throw new ModelsException('El campo Autor está vacío.');
            }
            $nombreA = Helper\Strings::clean_string(mb_strtoupper($nombreA, 'UTF-8'));
            
            $autor_existe = $this->db->select('*', 'autores', null, "autor = '".$nombreA."' AND id_autor != '$id'");
            if($autor_existe){
                throw new ModelsException('El autor '.$nombreA.' ya existe en la base de datos.');
            }

            $rutaA = Helper\Strings::url_amigable(mb_strtolower($nombreA, 'UTF-8'));
            
            $oferta = intval($http->request->get('eOferta'));
            $oPrecio = (real) $http->request->get('eoPrecio');
            $oPorcentaje = (real) $http->request->get('eoPorcentaje');
            $oFecha = $this->db->scape($http->request->get('eoFecha'));

            $autor = $this->autor($id);
            if(!$autor){
                throw new ModelsException("El autor no existe");
            }
            if($id==1){
            	if($autor["autor"] != $nombreA){
            	    throw new ModelsException("El nombre de este autor no puede ser editado.");
            	}
            }

            if($oferta == 0){
                
                $precio = 0;
                $porcentaje = 0;
                $oFecha = "";

                $this->db->real_query("UPDATE productos AS p 
                    LEFT JOIN productos_autores AS pa ON p.id=pa.id_producto
                    SET 
                    p.ofertadoPorCategoria = 0, 
                    p.ofertadoPorSubcategoria = 0, 
                    p.ofertadoPorEditorial = 0, 
                    p.ofertadoPorAutor = $oferta, 
                    p.oferta = 0, 
                    p.precioOferta = $precio, 
                    p.descuentoOferta = $porcentaje, 
                    p.finOferta = '$oFecha' 
                    WHERE pa.id_autor='$id'");
                
            }else{
                
                if($oPrecio == 0 && $oPorcentaje == 0){
                    throw new ModelsException('Es necesario especificar un valor para la oferta.');
                }
                if($oFecha == ""){
                    throw new ModelsException('Es necesario especificar la fecha en que finaliza la oferta.');
                }else{
                    $oFecha = $oFecha.' 23:59:59';
                }
                
                $producto = $this->db->select("*", "productos AS p", "INNER JOIN productos_autores AS pa ON p.id=pa.id_producto", "pa.id_autor = '$id'", null, "GROUP BY p.id");

                if($producto){
                    
                    $precio = $oPrecio;
                    $porcentaje = $oPorcentaje;
                    
                    foreach ($producto as $key => $value) {
                        
                        if($value['descontinuado'] == 'NO'){                    # NO APLICA PARA LOS DESCONTINUADOS
                        
                            $idProducto = $value["id"];
                            
                            if($precio == 0){
                                
                                $precioProducto = $value["precio"] - ( $value["precio"] * ($porcentaje/100) );
                                $porcentajeProducto = $porcentaje;
                                
                            }elseif($porcentaje == 0 && $value["precio"] != 0){
                                
                                $precioProducto = $precio;
                                $porcentajeProducto = 100 - ( ($precio/$value["precio"]) * 100 );
                                
                            }
                            
                            if($precioProducto > $value["precio"] || $value["precio"] == 0){
    
                                $this->db->real_query("UPDATE productos AS p 
                                LEFT JOIN productos_autores AS pa ON p.id=pa.id_producto
                                SET 
                                p.ofertadoPorCategoria = 0, 
                                p.ofertadoPorSubcategoria = 0, 
                                p.ofertadoPorEditorial = 0, 
                                p.ofertadoPorAutor = 0, 
                                p.oferta = 0, 
                                p.precioOferta = 0, 
                                p.descuentoOferta = 0, 
                                p.finOferta = '' 
                                WHERE p.id='$idProducto' AND pa.id_autor='$id'");
                        
                            }else{                            
                                
                                $this->db->real_query("UPDATE productos AS p 
                                LEFT JOIN productos_autores AS pa ON p.id=pa.id_producto
                                SET 
                                p.ofertadoPorCategoria = 0, 
                                p.ofertadoPorSubcategoria = 0, 
                                p.ofertadoPorEditorial = 0, 
                                p.ofertadoPorAutor = $oferta, 
                                p.oferta = 0, 
                                p.precioOferta = $precioProducto, 
                                p.descuentoOferta = $porcentajeProducto, 
                                p.finOferta = '$oFecha' 
                                WHERE p.id='$idProducto' AND pa.id_autor='$id'");    
                            
                            }
                            
                        }
                        
                    }
                    
                } else {
                    
                    $oferta = 0;
                    $precio = 0;
                    $porcentaje = 0;
                    $oFecha = "";
                    
                }
                
            }

            # Actualizar autor
            $this->db->update('autores', array(
                'autor' => $nombreA,
                'ruta' => $rutaA,
                'oferta' => $oferta,
                'precioOferta' => $precio,
                'descuentoOferta' => $porcentaje,
                'finOferta' => $oFecha
            ), "id_autor='$id'");

            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador){
                $administrador = $administrador;
                $perfiles = $administrador['perfiles'];
                foreach ($perfiles as $key => $value) {
                    if($value['principal']){
                        $perfil = $value['id_perfil'];
                    }
                }
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de autor '.$id.' '.$nombreA, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            if($oferta==1){
                $ofertaTexto = ($oPrecio==0) ? "a -".$oPorcentaje."%" : 'todo a $'.number_format($oPrecio,2);
                (new Model\Actividad)->registrarActividad('Notificacion', "Adición de oferta ($ofertaTexto) en autor $id ".$nombreA." con vigencia hasta $oFecha", $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El autor '.$id.' '.$nombreA.' se editó correctamente.');

        } catch (ModelsException $e) {
            
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }

    }
    
    /**
     * Elimina un autor 
     * 
     * @return array
    */ 
    public function eliminarAutor() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            $id = intval($http->request->get('id'));
            if($id==1){throw new ModelsException("Este autor no puede ser eliminado.");}
            // Traer dato de la autor por id
            $autor = $this->autor($id);
            if(!$autor){
                throw new ModelsException('El autor no existe.');
            }else{
                $productos = $this->db->select("*", "productos", null, "FIND_IN_SET({$id},autores)>0");

                if($productos){
                    return array('status' => 'info', 'title' => '¡Atención!','message' => 'El autor no puede ser eliminado ya que existen registros de productos asociados a este.');
                }else{

                    $rutaOferta = self::URL_ASSETS_WEBSITE.$autor["imgOferta"];

                    if($autor["oferta"] == 1){
                        if($autor["imgOferta"] != "assets/plantilla/img/ofertas/default/default.jpg"){
                            unlink($rutaOferta);
                        }
                    }

                    $eliminarAutor = $this->db->delete('autores', "id_autor = '$id'", 1);

                    $administrador = (new Model\Users)->getOwnerUser();
                    if($administrador){
                        $administrador = $administrador;
                        $perfiles = $administrador['perfiles'];
                        foreach ($perfiles as $key => $value) {
                            if($value['principal']){
                                $perfil = $value['id_perfil'];
                            }
                        }
                    }

                    (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de autor '.$id, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'El autor ha sido eliminado.');
                }
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
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
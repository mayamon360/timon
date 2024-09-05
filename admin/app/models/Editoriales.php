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
 * Modelo Editoriales
 */
 class Editoriales extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */

    const URL_ASSETS_WEBSITE = '../../';
    
    /**
     * Obtiene todas las editoriales
     * 
     * @return false|array
    */ 
    public function editoriales() {
        $editoriales = $this->db->select('*', 'editoriales', null, null, null, "ORDER BY id_editorial DESC");
        return $editoriales;
    }
    
    /**
     * Obtiene datos de una editorial según su id en la base de datos
     * 
     * @param int $id: Id de la editorial a obtener
     *
     * @return false|array[0]
    */ 
    public function editorial($id) {
        $editorial= $this->db->select("*", 'editoriales', null, "id_editorial='$id'", 1);
        if($editorial){
            return $editorial[0];
        }else{
            return false;
        }
    }
    
    /**
     * Obtiene las editoriales según el valor de un item
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array[0]
    */ 
    public function editorialesPor($item, $valor){
        $editoriales = $this->db->select('*', 'editoriales', null, "$item='$valor'", null, 'ORDER BY id_editorial DESC');
        if($editoriales){
            return $editoriales;
        }else{
            return false;
        }
    }
    
    /**
     * Retorna los datos de las editoriales para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarEditoriales() : array {
        global $config;
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }
        
        # Obtener la url de multimedia
        $paginaWeb = $config['build']['urlAssetsPagina'];

        //$editoriales = $this->editoriales();
        $editoriales = $this->db->query("SELECT e.*, COUNT(p.id) as productos FROM editoriales AS e INNER JOIN productos AS p ON e.id_editorial=p.id_editorial GROUP BY e.id_editorial ORDER BY e.id_editorial DESC");
        
        $data = [];
        if($editoriales){
            foreach ($editoriales as $key => $value) {
                if($value["editorial"] != 1){
                    
                    $infoData = [];
                    
                    $infoData[] = '<span class="badge bg-black" style="font-weight:800;">'.$value["id_editorial"].'</span>';
                    $infoData[] = '<a href="'.$paginaWeb.'editorial/'.$value["ruta"].'" target="_blank" data-toggle="tooltip" title="Abrir enlace"><strong>'.$value["editorial"].'</strong></a>';
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
                    
                    if($value["credito"] == 1){
                        $infoData[] = $estado = '<span class="badge bg-gray-light text-black credito'.$value["id_editorial"].'"><i class="fas fa-check fa-lg text-aqua credito" value="0" key="'.$value["id_editorial"].'" style="cursor:pointer; margin-right:5px;"></i> Si</span>';
                    }else{
                        $infoData[] = $estado = '<span class="badge bg-gray-light text-black credito'.$value["id_editorial"].'"><i class="fas fa-times fa-lg text-red credito" value="1" key="'.$value["id_editorial"].'" style="cursor:pointer; margin-right:5px;"></i> No</span>';
                    }
                    
                    $infoData[] = "<div class='btn-group'>
                        		    <button class='btn btn-sm btn-default obtenerEditorial' key='".$value["id_editorial"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt' data-toggle='tooltip' title='Editar'></i></button>
                            		<button class='btn btn-sm btn-default eliminar' key='".$value["id_editorial"]."'><i class='fas fa-trash-alt' data-toggle='tooltip' title='Eliminar'></i></button>
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
     * Cambia el estado de una editorial, afectando a productos
     *
     * 0:desactivado | 1:activado 
     *
     * @return array
    */ 
    public function estadoEditorial() : array {
        try {
            throw new ModelsException('No disponible por el momento.');
            /*global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $editorial= $this->editorial($id);
            if(!$editorial){
                throw new ModelsException('La editorial no existe.');
            }else{
                $this->db->update('editoriales',array('estado' => $estado),"id_editorial='$id'",1);
                $this->db->update('productos',array('estado' => $estado),"id_editorial='$id'");
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

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado de editorial '.$id.' '.$editorial['editorial'].' a '.$estado_txt, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');*/
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Cambia el estado de crédito de una editorial
     *
     * 0:no aplica | 1:si aplica 
     *
     * @return array
    */ 
    public function creditoEditorial() : array {
        try {
            
            global $http;
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){
               throw new ModelsException("No tienes permisos para realizar esta acción.");   
            }
            
            $id = intval($http->request->get('id'));                            # id editorial
            $credito = intval($http->request->get('credito'));                  # esatdo del credito (0:no - 1:si)
            
            $editorial= $this->editorial($id);                                  # obtener datos de la editorial 
            if(!$editorial){
                throw new ModelsException('La editorial no existe.');
            }else{
                
            	$clienteEditorial = $this->db->select("*", "clientes_editoriales", null, "id_editorial = '$id'",1);
            	
            	if($credito == 0 && $clienteEditorial){                         # Si se desea quitar el credito y la editorial ya esta asignada a clientes
            		throw new ModelsException('La editorial ya tiene clientes asignados, no es posible modificar está.');
            	}
            	
                $this->db->update('editoriales',array('credito' => $credito),"id_editorial='$id'",1);

            }

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
                $estado_txt = 'aplica';
            }else{
                $estado_txt = 'no aplica';
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado del crédito para la editorial '.$id.' '.$editorial['editorial'].' a '.$estado_txt, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Agrega una nueva editorial
     * 
     * @return array
    */ 
    public function agregarEditoriales() : array {
        try {
            global $http;
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            # Obtener los datos $_POST

            $nombreE = $this->db->scape( trim($http->request->get('nombreE') ) );
            if($nombreE == ""){
                throw new ModelsException('El campo Editorial está vació.');
            }
            $nombreE = Helper\Strings::clean_string(mb_strtoupper($nombreE, 'UTF-8'));
            $editorial_existe = $this->db->select('*', 'editoriales', null, "editorial = '".$nombreE."'");
            if($editorial_existe){
                throw new ModelsException('La editorial '.$nombreE.' ya existe en la base de datos.');
            }

            $rutaE = Helper\Strings::url_amigable(mb_strtolower($nombreE, 'UTF-8'));

            # Registrar editorial
            $insertar = $this->db->insert('editoriales', array(
                'editorial' => $nombreE,
                'ruta' => $rutaE,
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

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de editorial '.$insertar.' '.$nombreE, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La editorial '.$nombreC.' se agregó correctamente.', 'agregado' => $insertar);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de una editorial para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerEditorial() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;

            $id = intval($http->request->get('id'));

            if($id != "" && $id != 0){
                
                $editorial = $this->editorial($id);
                
                if($editorial){

                    $p = new Model\Productos;
                    $productos = $p->productosPor("id_editorial",$id);

                    if($productos){
                        
                        if($editorial["oferta"] == 0){
                            
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

                            if($editorial["descuentoOferta"] != 0){
                                
                                $oPrecio = "";
                                $pDisabled = "disabled";
                                $oDescuento = $editorial["descuentoOferta"];
                                $dDisabled = "";
                                
                            }else{
                                
                                $oPrecio = $editorial["precioOferta"];
                                $pDisabled = "";
                                $oDescuento = "";
                                $dDisabled = "disabled";
                                
                            }
                            
                            $oFecha = substr($editorial["finOferta"], 0, -9);
                        }
                        
                        $informacion = count($productos).' producto(s) de está editorial.';
                        $nameSelect = 'eOferta';
                        
                    }else{
                        
                        $options = '<option disabled selected>La oferta no aplica</option>';
                        $clase = 'hidden';
                        $oPrecio = "";
                        $pDisabled = "";
                        $oDescuento = "";
                        $dDisabled = "";
                        $oFecha = "";
                        $informacion = '0 productos de está editorial.';
                        $nameSelect = '';
                        
                    }

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="idE" id="idE" value="'.$editorial["id_editorial"].'">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon text-red">
                                                        <i class="fas fa-building"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-uppercase eValidarE" name="eNombreE" id="eNombreE" value="'.$editorial["editorial"].'" placeholder="Editorial" required>
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
                    throw new ModelsException('La editorial no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
            }    
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Edita una editorial
     * 
     * @return array
    */ 
    public function editarEditorial() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            $id = intval($http->request->get("idE"));

            $nombreE = $this->db->scape( trim($http->request->get('eNombreE') ) );
            if($nombreE == ""){
                throw new ModelsException('El campo Editorial está vacío.');
            }
            $nombreE = Helper\Strings::clean_string(mb_strtoupper($nombreE, 'UTF-8'));
            
            $editorial_existe = $this->db->select('*', 'editoriales', null, "editorial = '".$nombreE."' AND id_editorial != '$id'");
            if($editorial_existe){
                throw new ModelsException('La editorial '.$nombreE.' ya existe en la base de datos.');
            }

            $rutaE = Helper\Strings::url_amigable(mb_strtolower($nombreE, 'UTF-8'));
            
            $oferta = intval($http->request->get('eOferta'));
            $oPrecio = (real) $http->request->get('eoPrecio');
            $oPorcentaje = (real) $http->request->get('eoPorcentaje');
            $oFecha = $this->db->scape($http->request->get('eoFecha'));
            
            $editorial = $this->editorial($id);
            if(!$editorial){
                throw new ModelsException("La editorial no existe");
            }
            if($id==1){
            	if($editorial["editorial"] != $nombreE){
            	    throw new ModelsException("El nombre de está editorial no puede ser editada.");
            	}
            }

            if($oferta == 0){
                
                $precio = 0;
                $porcentaje = 0;
                $oFecha = "";

                $this->db->update('productos', array(
                    'ofertadoPorCategoria' => 0,
                    'ofertadoPorSubcategoria' => 0,
                    'ofertadoPorEditorial' => 0,
                    'ofertadoPorAutor' => 0,
                    'oferta' => 0,
                    'precioOferta' => $precio,
                    'descuentoOferta' => $porcentaje,
                    'finOferta' => $oFecha
                ), "id_editorial='$id'"); 
                
            }else{
                
                if($oPrecio == 0 && $oPorcentaje == 0){
                    throw new ModelsException('Es necesario especificar un valor para la oferta.');
                }
                if($oFecha == ""){
                    throw new ModelsException('Es necesario especificar la fecha en que finaliza la oferta.');
                }else{
                    $oFecha = $oFecha.' 23:59:59';
                }

                $p = new Model\Productos;
                $producto = $p->productosPor('id_editorial',$id);

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
                                
                                $this->db->update('productos', array(
                                    'ofertadoPorCategoria' => 0,
                                    'ofertadoPorSubcategoria' => 0,
                                    'ofertadoPorEditorial' => 0,
                                    'ofertadoPorAutor' => 0,
                                    'oferta' => 0,
                                    'precioOferta' => 0,
                                    'descuentoOferta' => 0,
                                    'finOferta' => ''
                                ), "id='$idProducto' AND id_editorial='$id'",1);
                                
                            }else{
                                
                                $this->db->update('productos', array(
                                    'ofertadoPorCategoria' => 0,
                                    'ofertadoPorSubcategoria' => 0,
                                    'ofertadoPorEditorial' => $oferta,
                                    'ofertadoPorAutor' => 0,
                                    'oferta' => 0,
                                    'precioOferta' => $precioProducto,
                                    'descuentoOferta' => $porcentajeProducto,
                                    'finOferta' => $oFecha
                                ), "id='$idProducto' AND id_editorial='$id'",1);
                                
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

            # Actualizar editorial
            $this->db->update('editoriales', array(
                'editorial' => $nombreE,
                'ruta' => $rutaE,
                'oferta' => $oferta,
                'precioOferta' => $precio,
                'descuentoOferta' => $porcentaje,
                'finOferta' => $oFecha
            ), "id_editorial='$id'");

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

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de editorial '.$id.' '.$nombreE, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            if($oferta==1){
                $ofertaTexto = ($oPrecio==0) ? "a -".$oPorcentaje."%" : 'todo a $'.number_format($oPrecio,2);
                (new Model\Actividad)->registrarActividad('Notificacion', "Adición de oferta ($ofertaTexto) en editorial $id ".$nombreE." con vigencia hasta $oFecha", $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La editorial '.$id.' '.$nombreE.' se editó correctamente.');

        } catch (ModelsException $e) {
            
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }

    }
    
    /**
     * Elimina una editorial 
     * 
     * @return array
    */ 
    public function eliminarEditorial() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            $id = intval($http->request->get('id'));
            if($id==1){throw new ModelsException("Está editorial no puede ser eliminada.");}
            // Traer dato de la editorial por id
            $editorial = $this->editorial($id);
            if(!$editorial){
                throw new ModelsException('La editorial no existe.');
            }else{
                $p = new Model\Productos;
                $productos = $p->ProductosPor('id_editorial', $id);

                if($productos){
                    return array('status' => 'info', 'title' => '¡Atención!','message' => 'La editorial no puede ser eliminada ya que existen registros de productos asociados a está.');
                }else{

                    $rutaOferta = self::URL_ASSETS_WEBSITE.$editorial["imgOferta"];

                    if($editorial["oferta"] == 1){
                        if($editorial["imgOferta"] != "assets/plantilla/img/ofertas/default/default.jpg"){
                            unlink($rutaOferta);
                        }
                    }

                    $eliminarEditorial = $this->db->delete('editoriales', "id_editorial = '$id'", 1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de editorial '.$id, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'La editorial ha sido eliminada.');
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
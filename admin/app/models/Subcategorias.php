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
 * Modelo Subcategorias
 */
class Subcategorias extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */

    const URL_ASSETS_WEBSITE = '../../';

    /**
     * Obtiene las subcategorías relacionadas a una categoría según su id en la base de datos
     *
     * @param int $id: Id de la categoría a obtener
     * 
     * @return false|array
    */ 
    public function subcategorias($id){
        $subcategorias = $this->db->select('*', 'subcategorias', null, "id_categoria='$id'");
        return $subcategorias;
    }

    /**
     * Obtiene datos de una subcategoría según su id en la base de datos, haciendo INNER JOIN a cabeceras
     *
     * @param int $id: Id de la categoría a obtener
     * 
     * @return false|array[0]
    */ 
    public function subcategoria($id) {
        $subcategoria = $this->db->select("s.id, cb.id AS idCb, s.id_categoria, s.subcategoria, s.ruta, s.estado, cb.descripcion, cb.palabrasClave, cb.portada, s.ofertaCategoria, s.oferta, s.precioOferta, s.descuentoOferta, s.imgOferta, s.finOferta", 'subcategorias AS s', 'INNER JOIN cabeceras AS cb ON s.ruta=cb.ruta', "s.id='$id'", 1);
        if($subcategoria){
            return $subcategoria[0];
        }else{
            return false;
        }
    }

    /**
     * Obtiene todas las categorías haciendo INNER JOIN a la tabla cabeceras y a la tabla categorias
     *
     * 
     * @return false|array
    */ 
    public function subcategoriasCompletas(){
        $subcategorias = $this->db->select("
        sb.id, c.categoria, sb.subcategoria, sb.ruta, 
        sb.estado, cb.descripcion, cb.palabrasClave, 
        cb.portada, sb.oferta, sb.ofertaCategoria, 
        sb.precioOferta, sb.descuentoOferta, sb.imgOferta, 
        sb.finOferta", 
        "subcategorias AS sb", 
        "INNER JOIN categorias AS c ON sb.id_categoria=c.id INNER JOIN cabeceras AS cb ON sb.ruta=cb.ruta", 
        null, null, "GROUP BY sb.id ORDER BY sb.id DESC");
        return $subcategorias;
    }

    /**
     * Obtiene las subcategorías según el valor de un item
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array[0]
    */ 
    public function subcategoriasPor($item, $valor){
        $subcategorias = $this->db->select('*', 'subcategorias', null, "$item='$valor'", null, 'ORDER BY id DESC');
        if($subcategorias){
            return $subcategorias;
        }else{
            return false;
        }
    }

    /**
     * Obtiene las subcategorías relacionadas a una categoría según su id en la base de datos para cargarlas en los options del select 
     * 
     * @return array
    */ 
    public function selectSubcategorias() : array {
        global $http;
        $categoria = intval($http->request->get('categoria'));
        $subcategoria = $this->subcategoriasPor('id_categoria', $categoria);
        $select = "";
        if(!$subcategoria){
            $select .= '<option></option><option value="1">Sin subcategoría</option>';
            return array('status' => 'vacio', 'select' => $select);
        }else{
            if($categoria == 1){
            	$select .= '<option></option><option value="1">Sin subcategoría</option>';
            }else{
                $select .= '<option></option><option value="1">Sin subcategoría</option><optgroup label="Subcategorías disponibles" id="subcategorias">';
	            foreach ($subcategoria as $key => $value) {
	            	if($value["id"] != 1){
	                	$select .= '<option value="'.$value["id"].'">'.$value["subcategoria"].'</option>';
	                }
	            }
	        $select .= '</optgroup>';
            }
            return array('status' => 'lleno', 'select' => $select);
        }
    }

    /**
     * Retorna los datos de las subcategorías para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarSubcategorias() : array {
        global $config;
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }
        
        # Obtener la url de multimedia
        $paginaWeb = $config['build']['urlAssetsPagina'];
        
        $subcategorias = $this->subcategoriasCompletas();

        $data = [];
        if($subcategorias){
            foreach ($subcategorias as $key => $value) {
                if($value["id"] != 1){
                    $productos = (new Model\Productos)->productosPor('idSubcategoria',$value['id']);

                    $infoData = [];
                    
                    $infoData[] = '<span class="badge bg-black" style="font-weight:800;">'.$value["id"].'</span>';
                    
                    $infoData[] = '>> '.$value["categoria"];
                    
                    $infoData[] = '<a href="'.$paginaWeb.'libros/'.$value["ruta"].'/'.$value["id"].'" target="_blank" data-toggle="tooltip" title="Abrir enlace"><strong>'.$value["subcategoria"].'</strong></a>';
                    
                    $total_productos = ($productos == false) ? '0' : count($productos);
                    $infoData[] = $total_productos;
                    
                    if($value["oferta"] != 0 || $value["ofertaCategoria"] != 0){
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
                    
                    if($value["id"] != 1){
                        $infoData[] = '<div class="btn-group">
                                        <button class="btn btn-sm btn-default obtenerSubcategoria" key="'.$value["id"].'" data-toggle="modal" data-target="#modalEditar"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="Editar"></i></button>
                                        <button class="btn btn-sm btn-default eliminar" key="'.$value["id"].'"><i class="fas fa-trash-alt" data-toggle="tooltip" title="Eliminar"></i></button>
                                    </div>';
                    }else{
                        $infoData[] = "<div class='btn-group'>
                                        <button class='btn btn-sm btn-default obtenerSubcategoria' key='".$value["id"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt' data-toggle='tooltip' title='Editar'></i></button>
                                    </div>";
                    }
                    

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
     * Cambia el estado de una categoría, afectando a subcategorías, productos y banner asociados a está
     *
     * 0:desactivado | 1:activado
     * 
     * @return array
    */ 
    public function estadoSubcategoria() : array {
        try {
            throw new ModelsException('No disponible por el momento.');
            /*
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $subcategoria = $this->subcategoria($id);
            if(!$subcategoria){
                throw new ModelsException('La subcategoría no existe.');
            }else{
                $p = new Model\Productos;
                $productos = $p->productosPor("idSubcategoria",$id);
                //if(!$productos){throw new ModelsException('Para activar la categoría es necesario agregar productos asociados a está..');}
                $c = new Model\Categorias;
                $categoria = $c->categoria($subcategoria["id_categoria"]);
                if($categoria["estado"] == 0){
                    throw new ModelsException('Para activar la subcatgoría es necesario activar la categoría '.$categoria["categoria"].'.');
                }else{
                    $this->db->update('subcategorias',array('estado' => $estado),"id='$id'");
                    $this->db->update('anuncios',array('estado' => $estado),"ruta='".$subcategoria["ruta"]."'",1);
                    $this->db->update('productos',array('estado' => $estado),"idSubcategoria='$id'");
                } 
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
            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado de subcategoría '.$id.' '.$subcategoria['subcategoria'].' a '.$estado_txt, $perfil, $administrador['id_user'], 5, date('Y-m-d H:i:s'), 0);
            return array('status' => 'success');
            */
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

     /**
     * Agrega una nueva subcategoría
     * 
     * @return array
    */ 
    public function agregarSubcategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;

            $idCategoria = intval($http->request->get('categoria'));
            if($idCategoria == 0){
                throw new ModelsException('Seleccione una categoría.');
            }
            $categoria = (new Model\Categorias)->categoria($idCategoria);
            if(!$categoria){
                throw new ModelsException('La categoría seleccionada no existe.');
            }

            $nombreS = $this->db->scape(trim($http->request->get('nombreS')));
            if($nombreS == ""){
                throw new ModelsException('El campo Subcategoría está vacío.');
            }
            $nombreS = Helper\Strings::clean_string(mb_strtoupper($nombreS, 'UTF-8'));
            $subcategorias = $this->db->select('*', 'subcategorias', null, "subcategoria = '$nombreS' AND id_categoria='$idCategoria'");
            if($subcategorias){
                throw new ModelsException($nombreS.' ya existe en ' . $categoria["categoria"] . '.');
            }
            
            $rutaS = Helper\Strings::url_amigable(mb_strtolower($nombreS, 'UTF-8'));
            
            $desc = $this->db->scape($http->request->get('desc'));
            if($desc == ""){
                $desc = "Libros de ".$nombreS;
            }
            
            $pClave = $this->db->scape($http->request->get('pClave'));
            if($pClave == ""){
                $pClave = "Libros de ".$nombreS;
            }

            # Registrar cabeceras
            $this->db->insert('cabeceras', array(
                'ruta' => $rutaS,
                'titulo' => $nombreS,
                'descripcion' => $desc,
                'palabrasClave' => $pClave,
                'portada' => "assets/plantilla/img/cabeceras/default/default.jpg"
            ));

            # Registrar subcategoría
            $insertar = $this->db->insert('subcategorias', array(
                'id_categoria' => $idCategoria,
                'subcategoria' => $nombreS,
                'ruta' => $rutaS,
                'oferta' => 0,
                'precioOferta' => '',
                'descuentoOferta' => '',
                'finOferta' => '',
                'estado' => 1
            ));

            # Registrar banner
            $this->db->insert('anuncios', array(
                'ruta' => $rutaS,
                'imagen' => 'assets/plantilla/img/banner/default/default.jpg',
                'tipo' => 'subcategoria',
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

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de subcategoría '.$insertar.' '.$nombreS, $perfil, $administrador['id_user'], 5, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La subcategoría '.$nombreS.' se agregó correctamente.');    
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de una subcategoría para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerSubcategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            $id = intval($http->request->get('id'));

            if($id != "" && $id != 0){
                
                $c = new Model\Categorias;
                $categorias = $c->categorias();

                $subcategoria = $this->subcategoria($id);
                
                if($subcategoria){

                    $p = new Model\Productos;
                    $productos = $p->productosPor("idSubcategoria",$id);

                    $ofertadaPorCategoria = false;

                    if($productos){
                        
                        $informacion = count($productos).' productos en está subcategoría.';
                        $nameSelect = 'eOferta';
                        
                        if($subcategoria["ofertaCategoria"] == 0 && $subcategoria["oferta"] == 0){
                            
                            $options = '<option value="0" selected>Sin oferta</option><option value="1">Con oferta</option>';
                            $clase = 'hidden';
                            $oPrecio = "";
                            $pDisabled = "";
                            $oDescuento = "";
                            $dDisabled = "";
                            $oFecha = "";

                        }elseif($subcategoria["ofertaCategoria"] == 0 && $subcategoria["oferta"] == 1){
                            
                            $options = '<option value="0">Sin oferta</option><option value="1" selected>Con oferta</option>';
                            $clase = 'show';
                            
                            if($subcategoria["descuentoOferta"] != 0){
                                
                                $oPrecio = "";
                                $pDisabled = "disabled";
                                $oDescuento = $subcategoria["descuentoOferta"];
                                $dDisabled = "";
                                
                            }else{
                                
                                $oPrecio = $subcategoria["precioOferta"];
                                $pDisabled = "";
                                $oDescuento = "";
                                $dDisabled = "disabled";
                                
                            }
                            
                            $oFecha = substr($subcategoria["finOferta"], 0, -9);
                            
                        }elseif($subcategoria["ofertaCategoria"] == 1 && $subcategoria["oferta"] == 0){
                            
                            $informacion = 'La subcategoría ya se encuentra ofertada por su categoría.';
                            $nameSelect = '';
                            $options = '<option disabled selected>La oferta por subcategoría no aplica</option>';
                            $clase = 'hidden';
                            $oPrecio = "";
                            $pDisabled = "";
                            $oDescuento = "";
                            $dDisabled = "";
                            $oFecha = "";
                            
                            $ofertadaPorCategoria = true;
                        }
                        
                    }else{
                        
                        $options = '<option disabled selected>La oferta no aplica</option>';
                        $clase = 'hidden';
                        $oPrecio = "";
                        $pDisabled = "";
                        $oDescuento = "";
                        $dDisabled = "";
                        $oFecha = "";
                        $oImg = "assets/plantilla/img/ofertas/default/default.jpg";
                        $informacion = '0 productos en está subcategoría.';
                        $nameSelect = '';
                        
                    }

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="idS" id="idS" value="'.$subcategoria["id"].'">
                                    <input type="hidden" name="idCb" id="idCb" value="'.$subcategoria["idCb"].'">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon text-red">
                                                        <i class="fas fa-tag"></i>
                                                    </span>
                                                    <select class="form-control js-example-placeholder-single js-states seleccionarCategoria" name="categoria" style="width: 100%;" lang="es" data-placeholder="CATEGORÍA" data-allow-clear="true" required>
                                                        <option></option>
                                                        <optgroup label="Categorías disponibles">';
                                                        foreach ($categorias as $key => $value) {
                                                            if($subcategoria["id_categoria"] == $value["id"]){
                                                                $formulario .= '<option value="'.$value["id"].'" selected="selected">'.$value["categoria"].'</option>';    
                                                            }else{
                                                                $formulario .= '<option value="'.$value["id"].'">'.$value["categoria"].'</option>'; 
                                                            }
                                                        }
                                                        $formulario .= '
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon text-red">
                                                        <i class="fas fa-tag"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-uppercase eValidarS" name="eNombreS" id="eNombreS" value="'.$subcategoria["subcategoria"].'" placeholder="SUBCATEGORÍA" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <textarea class="form-control eDesc" rows="5" name="eDesc" id="eDesc" style="resize: none;" placeholder="Descripción" required>'.$subcategoria["descripcion"].'</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-tags"></i>
                                                    </span>
                                                    <input type="text" class="form-control epClave tagsinput" name="epClave" id="epClave" value="'.$subcategoria["palabrasClave"].'" data-role="tagsinput" placeholder="Palabras clave (separadas por coma)" required>
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
                                        </div>';
                                        if($ofertadaPorCategoria){
                                            $formulario .= '<input type="hidden" name="ofertaBD" id="ofertaBD" value="1">';
                                        }
                                        $formulario .= '<div class="eOfertaDetalles '.$clase.'">
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
                    throw new ModelsException('La subcategoría no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
            }   
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Edita una categoría
     * 
     * @return array
    */ 
    public function editarSubcategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            $idCategoria = intval($http->request->get('categoria'));
            if($idCategoria == 0){
                throw new ModelsException('Seleccione una categoría.');
            }
            $categoria = (new Model\Categorias)->categoria($idCategoria);
            if(!$categoria){
                throw new ModelsException('La categoría seleccionada no existe.');
            }
            
            $id = intval($http->request->get("idS"));
            $idCb = intval($http->request->get("idCb"));
            
            $nombreS = $this->db->scape(trim($http->request->get('eNombreS')));
            if($nombreS == ""){
                throw new ModelsException('El campo Subcategoría está vacío.');
            }
            
            $nombreS = Helper\Strings::clean_string(mb_strtoupper($nombreS, 'UTF-8'));
            $subcategorias = $this->db->select('*', 'subcategorias', null, "subcategoria = '".$nombreS."' AND id != '$id'");
            if($subcategorias){
                throw new ModelsException('La categoría '.$nombreS.' ya existe en la base de datos.');
            }

            $rutaS = Helper\Strings::url_amigable(mb_strtolower($nombreS, 'UTF-8'));
            
            $desc = $this->db->scape($http->request->get('eDesc'));
            if($desc == ""){
                $desc = "Libros de ".$nombreS;
            }
            
            $pClave = $this->db->scape($http->request->get('epClave'));
            if($pClave == ""){
                $pClave = "Libros de ".$nombreS;
            }
            
            $oferta = intval($http->request->get('eOferta'));
            $ofertaBD = intval($http->request->get('ofertaBD'));
            $oPrecio = (real) $http->request->get('eoPrecio');
            $oPorcentaje = (real) $http->request->get('eoPorcentaje');
            $oFecha = $this->db->scape($http->request->get('eoFecha'));

            $subcategoria = $this->subcategoria($id);
            if(!$subcategoria){
                throw new ModelsException("La subcategoría a editar no es valida");
            }
            if($id==1){
            	if($subcategoria["subcategoria"] != $nombreS){
            	    throw new ModelsException("El nombre de está subcategoría no puede ser editada.");
            	}
            }

            $ofertadaPorCategoria = 0;

            if($oferta == 0){

                if($ofertaBD == 1){
                    
                    $ofertadaPorCategoria = $subcategoria['ofertaCategoria'];
                    $precio = $subcategoria['precioOferta'];
                    $porcentaje = $subcategoria['descuentoOferta'];
                    $oFecha = $subcategoria['finOferta'];
                    
                }else{
                    
                    $precio = 0;
                    $porcentaje = 0;
                    $oFecha = "";
                    
                }

                $this->db->update('productos', array(
                    'ofertadoPorCategoria' => $ofertadaPorCategoria,
                    'ofertadoPorSubcategoria' => $oferta,
                    'ofertadoPorEditorial' => 0,
                    'ofertadoPorAutor' => 0,
                    'oferta' => 0,
                    'precioOferta' => $precio,
                    'descuentoOferta' => $porcentaje,
                    'finOferta' => $oFecha
                ), "idSubcategoria='$id'"); 
                
            }else{
                
                if($subcategoria["ofertaCategoria"] == 1){
                    
                    throw new ModelsException('La subcategoría ya se encuentra ofertada por su categoría.');
                    
                } else {
                    
                    if($oPrecio == 0 && $oPorcentaje == 0){
                        throw new ModelsException('Es necesario especificar un valor para la oferta.');
                    }
                    
                    if($oFecha == ""){
                        throw new ModelsException('Es necesario especificar la fecha en que finaliza la oferta.');
                    } else {
                        $oFecha = $oFecha.' 23:59:59';
                    }

                    $p = new Model\Productos;
                    $producto = $p->productosPor('idSubcategoria',$id);

                    if($producto){
                        
                        $precio = $oPrecio;
                        $porcentaje = $oPorcentaje;
                        
                        foreach ($producto as $key => $value) {
                            
                            if($value['descontinuado'] == 'NO'){                    # NO APLICA PARA LOS DESCONTINUADOS
                            
                                $idProducto = $value["id"];
                                
                                if($precio == 0){
                                    
                                    $precioProducto = $value["precio"]-($value["precio"]*($porcentaje/100));
                                    $porcentajeProducto = $porcentaje;
                                    
                                }elseif($porcentaje == 0 && $value["precio"] != 0){
                                    
                                    $precioProducto = $precio;
                                    $porcentajeProducto = 100-(($precio/$value["precio"])*100);
                                    
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
                                        'finOferta' => ""
                                    ), "id='$idProducto' AND idSubcategoria='$id'",1);
                                }else{
                                    $this->db->update('productos', array(
                                        'ofertadoPorCategoria' => $ofertadaPorCategoria,
                                        'ofertadoPorSubcategoria' => $oferta,
                                        'ofertadoPorEditorial' => 0,
                        		        'ofertadoPorAutor' => 0,
                                        'oferta' => 0,
                                        'precioOferta' => $precioProducto,
                                        'descuentoOferta' => $porcentajeProducto,
                                        'finOferta' => $oFecha
                                    ), "id='$idProducto' AND idSubcategoria='$id'",1);
                                    
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
            }

            if($subcategoria["subcategoria"] != $nombreS){
                # Actualizar banner
                $b = new Model\Banners;
                $banner = $b->bannerPor('ruta',$subcategoria["ruta"]);
                if($banner){

                    $directorio = self::URL_ASSETS_WEBSITE.$banner["imagen"];
                    if($directorio != self::URL_ASSETS_WEBSITE.'assets/plantilla/img/banner/default/default.jpg' && file_exists($directorio)){
                        $infoFile = pathinfo($directorio);
                        $extension = $infoFile['extension'];
                        rename($directorio, self::URL_ASSETS_WEBSITE."assets/plantilla/img/banner/".$rutaS.'.'.$extension);
                        $urlBanner = "assets/plantilla/img/banner/".$rutaS.'.'.$extension;
                    }else{
                        $urlBanner = $banner["imagen"];
                    }

                    $actualizarBanner = $this->db->update('anuncios',array(
                        'ruta' => $rutaS,
                        'imagen' => $urlBanner
                    ), "ruta='".$subcategoria["ruta"]."'", 1);
                }
            }

            # Actualizar cabeceras
            $this->db->update('cabeceras', array(
                'ruta' => $rutaS,
                'titulo' => $nombreS,
                'descripcion' => $desc,
                'palabrasClave' => $pClave
            ), "id='$idCb'");

            # Actualizar subcategoría
            $this->db->update('subcategorias', array(
                'id_categoria' => $idCategoria,
                'subcategoria' => $nombreS,
                'ruta' => $rutaS,
                'ofertaCategoria' => $ofertadaPorCategoria,
                'oferta' => $oferta,
                'precioOferta' => $precio,
                'descuentoOferta' => $porcentaje,
                'finOferta' => $oFecha
            ), "id='$id'");

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

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de subcategoría '.$id.' '.$nombreS, $perfil, $administrador['id_user'], 5, date('Y-m-d H:i:s'), 0);

            if($oferta==1){
                $ofertaTexto = ($oPrecio==0) ? "a -".$oPorcentaje."%" : 'todo a $'.number_format($oPrecio,2);
                (new Model\Actividad)->registrarActividad('Notificacion', "Adición de oferta ($ofertaTexto) en subcategoría $id ".$nombreS." con vigencia hasta $oFecha", $perfil, $administrador['id_user'], 5, date('Y-m-d H:i:s'), 0);
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La subcategoría '.$id.' '.$nombreS.' se editó correctamente.');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Elimina el registro de una subcategoría por el id
     * 
     * @return array
    */ 
    public function eliminarSubcategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            $id = intval($http->request->get('id'));
            if($id==1){throw new ModelsException("Está subcategoría no puede ser eliminada.");}
            // Traer dato de la categoría por id
            $subcategoria = $this->subcategoria($id);
            if(!$subcategoria){
                throw new ModelsException('La subcategoría no existe.');
            }else{
                $p = new Model\Productos;
                $productos = $p->ProductosPor('idSubcategoria', $id);

                $b = new Model\Banners;
                $banner = $b->bannerPor('ruta',$subcategoria["ruta"]);

                if($productos){
                    return array('status' => 'info', 'title' => '¡Atención!','message' => 'La subcategoría no puede ser eliminada ya que existen registros de productos asociados a está.');
                }else{
                    
                    $rutaPortada = self::URL_ASSETS_WEBSITE.$subcategoria["portada"];
                    $rutaOferta = self::URL_ASSETS_WEBSITE.$subcategoria["imgOferta"];
                    $rutaBanner = self::URL_ASSETS_WEBSITE.$banner["imagen"];

                    if($subcategoria["portada"] != "assets/plantilla/img/cabeceras/default/default.jpg"){
                        unlink($rutaPortada);
                    }
                    if($subcategoria["oferta"] == 1){
                        if($subcategoria["imgOferta"] != "assets/plantilla/img/ofertas/default/default.jpg"){
                            unlink($rutaOferta);
                        }
                    }

                    if($banner["imagen"] != "" && $banner["imagen"] != "assets/plantilla/img/banner/default/default.jpg"){
                        unlink($rutaBanner);
                    }
                    $eliminarCategoria = $this->db->delete('subcategorias', "id='$id'", 1);
                    $eliminarCabeceras = $this->db->delete('cabeceras', "id='".$subcategoria["idCb"]."'", 1);
                    $eliminarBanner = $this->db->delete('anuncios', "ruta='".$subcategoria["ruta"]."'", 1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de subcategoría '.$id, $perfil, $administrador['id_user'], 5, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'La subcategoría ha sido eliminada.');
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
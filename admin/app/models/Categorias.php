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
 * Modelo Categorias
 */
class Categorias extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */

    const URL_ASSETS_WEBSITE = '../../';

    /**
     * Obtiene todas las categorías
     * 
     * @return false|array
    */ 
    public function categorias() {
        $categorias = $this->db->select('*', 'categorias', null, "id != 1", null, "ORDER BY categoria ASC");
        return $categorias;
    }

    /**
     * Obtiene todas las categorías haciendo INNER JOIN a la tabla cabeceras
     * 
     * @return false|array
    */ 
    public function categoriasCompletas() {
       $categorias = $this->db->select("c.id, cb.id AS idCb, c.categoria, c.ruta, c.estado, cb.descripcion, cb.palabrasClave, cb.portada, c.oferta, c.precioOferta, c.descuentoOferta, c.imgOferta, c.finOferta", "categorias AS c", 
            "INNER JOIN cabeceras AS cb ON c.ruta=cb.ruta", null, null, "ORDER BY c.id DESC");
        return $categorias;
    }

    /**
     * Obtiene datos de una categoría según su id en la base de datos, haciendo INNER JOIN a cabeceras
     * 
     * @param int $id: Id de la categoría a obtener
     *
     * @return false|array[0]
    */ 
    public function categoria($id) {
        $categoria = $this->db->select("c.id, cb.id AS idCb, c.categoria, c.ruta, c.estado, cb.descripcion, cb.palabrasClave, cb.portada, c.oferta, c.precioOferta, c.descuentoOferta, c.imgOferta, c.finOferta", 'categorias AS c', 'INNER JOIN cabeceras AS cb ON c.ruta=cb.ruta', "c.id='$id'", 1);
        if($categoria){
            return $categoria[0];
        }else{
            return false;
        }
    }

    /**
     * Obtiene las categorías según el valor de un item
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array[0]
    */ 
    public function categoriasPor($item, $valor){
        $categorias = $this->db->select('*', 'categorias', null, "$item='$valor'", null, 'ORDER BY id DESC');
        if($categorias){
            return $categorias;
        }else{
            return false;
        }
    }

    /**
     * Retorna los datos de las categorías para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarCategorias() : array {
        global $config;
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }

        # Obtener la url de multimedia
        $paginaWeb = $config['build']['urlAssetsPagina'];

        $categorias = $this->categoriasCompletas();
        $data = [];
        if($categorias){
            foreach ($categorias as $key => $value) {
                if($value["id"] != 1){
                    $subcategorias = (new Model\Subcategorias)->subcategoriasPor('id_categoria',$value['id']);
                    $productos = (new Model\Productos)->productosPor('idCategoria',$value['id']);
    
                    $infoData = [];
                    $infoData[] = '<span class="badge bg-black" style="font-weight:800;">'.$value["id"].'</span>';
                    $infoData[] = '<a href="'.$paginaWeb.'libros/'.$value["ruta"].'/'.$value["id"].'" target="_blank" data-toggle="tooltip" title="Abrir enlace"><b>'.$value["categoria"].'</b></a>';
                    
                    $total_subcategorias = ($subcategorias == false) ? '0' : count($subcategorias);
                    $total_productos = ($productos == false) ? '0' : count($productos);
                    $infoData[] = $total_subcategorias.' / '.$total_productos;
                    
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
                    
                    if($value['id'] != 1){           	
                    	$infoData[] = "<div class='btn-group'>
                                        <button class='btn btn-sm btn-default obtenerCategoria' key='".$value["id"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt' data-toggle='tooltip' title='Editar'></i></button>
                                        <button class='btn btn-sm btn-default eliminar' key='".$value["id"]."'><i class='fas fa-trash-alt' data-toggle='tooltip' title='Eliminar'></i></button>
                                    </div>";
                    }else{
                    	$infoData[] = "<div class='btn-group'>
                                        <button class='btn btn-sm btn-default obtenerCategoria' key='".$value["id"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt' data-toggle='tooltip' title='Editar'></i></button>
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
    public function estadoCategoria() : array {
        try {
            throw new ModelsException('No disponible por el momento.');
            /*global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $categoria = $this->categoria($id);
            if(!$categoria){
                throw new ModelsException('La categoría no existe.');
            }else{
                $p = new Model\Productos;
                $productos = $p->productosPor("idCategoria",$id);
                //if(!$productos){throw new ModelsException('Para activar la categoría es necesario agregar productos asociados a está.');}
                $this->db->update('categorias',array('estado' => $estado),"id='$id'",1);
                $this->db->update('subcategorias',array('estado' => $estado),"id_categoria='$id'");
                $this->db->update('anuncios',array('estado' => $estado),"ruta='".$categoria["ruta"]."'",1);
                $this->db->update('productos',array('estado' => $estado),"idCategoria='$id'");
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
            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado de categoría '.$id.' '.$categoria['categoria'].' a '.$estado_txt, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);
            return array('status' => 'success');*/
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega una nueva categoría 
     * 
     * @return array
    */ 
    public function agregarCategoria() : array {
        try {
            global $http;
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
        
            # Obtener los datos $_POST

            $nombreC = $this->db->scape(trim($http->request->get('nombreC')));                          # Nombre de la categoria
            if($nombreC == ""){                                                                         
                throw new ModelsException('El campo Categoría está vació.');
            }
            $nombreC = Helper\Strings::clean_string(mb_strtoupper($nombreC, 'UTF-8'));
            $categorias = $this->db->select('*', 'categorias', null, "categoria = '".$nombreC."'");
            if($categorias){
                throw new ModelsException('La categoría '.$nombreC.' ya existe en la base de datos.');
            }
            
            $rutaC = Helper\Strings::url_amigable(mb_strtolower($nombreC, 'UTF-8'));                    # Ruta URL
            
            $desc = $this->db->scape($http->request->get('desc'));                                      # Descripcion
            if($desc == ""){
                $desc = "Libros de ".$nombreC;
            }
            $pClave = $this->db->scape($http->request->get('pClave'));                                  # Palabras clave
            if($pClave == ""){
                $pClave = "Libros de ".$nombreC;
            }

            # Registrar cabeceras
            $this->db->insert('cabeceras', array(
                'ruta' => $rutaC,
                'titulo' => $nombreC,
                'descripcion' => $desc,
                'palabrasClave' => $pClave,
                'portada' => "assets/plantilla/img/cabeceras/default/default.jpg"
            ));

            # Registrar categoría
            $insertar = $this->db->insert('categorias', array(
                'categoria' => $nombreC,
                'ruta' => $rutaC,
                'oferta' => 0,
                'precioOferta' => '',
                'descuentoOferta' => '',
                'finOferta' => '',
                'estado' => 1
            ));

            # Registrar banner
            $this->db->insert('anuncios', array(
                'ruta' => $rutaC,
                'imagen' => 'assets/plantilla/img/banner/default/default.jpg',
                'tipo' => 'categoria',
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

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de categoría '.$insertar.' '.$nombreC, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La categoría '.$nombreC.' se agregó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de una categoría para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerCategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
        
            global $http;

            $id = intval($http->request->get('id'));
            
            if($id != "" && $id != 0){
                
                $categoria = $this->categoria($id);
                
                if($categoria){

                    $p = new Model\Productos;
                    $productos = $p->productosPor("idCategoria",$id);

                    if($productos){                                             # Si hay productos asociados a la categoria
                        
                        if($categoria["oferta"] == 0){                          # Si la categoria NO esta ofertada
                            
                            $options = '<option value="0" selected>Sin oferta</option><option value="1">Con oferta</option>';
                            $clase = 'hidden';
                            $oPrecio = "";
                            $pDisabled = "";
                            $oDescuento = "";
                            $dDisabled = "";
                            $oFecha = "";
                            
                        }else{                                                  # Si la cetgoria SI esta ofertada
                            
                            $options = '<option value="0">Sin oferta</option><option value="1" selected>Con oferta</option>';
                            $clase = 'show';
                            
                            if($categoria["descuentoOferta"] != 0){             # Si la oferta es por descuento
                            
                                $oPrecio = "";
                                $pDisabled = "disabled";
                                $oDescuento = $categoria["descuentoOferta"];
                                $dDisabled = "";
                                
                            }else{                                              # Si la oferta es por precio
                            
                                $oPrecio = $categoria["precioOferta"];
                                $pDisabled = "";
                                $oDescuento = "";
                                $dDisabled = "disabled";
                                
                            }
                            
                            $oFecha = substr($categoria["finOferta"], 0, -9);
                            
                        }
                        
                        $informacion = count($productos).' producto(s) en está categoría.';
                        $nameSelect = 'eOferta';
                        
                    }else{                                                      # Si no hay productos asociados a la categoria, se desactiva el select de oferta
                        
                        $options = '<option disabled selected>La oferta no aplica</option>';
                        $clase = 'hidden';
                        $oPrecio = "";
                        $pDisabled = "";
                        $oDescuento = "";
                        $dDisabled = "";
                        $oFecha = "";
                        $informacion = '0 productos en está categoría.';
                        $nameSelect = '';
                        
                    }

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="idC" id="idC" value="'.$categoria["id"].'">
                                    <input type="hidden" name="idCb" id="idCb" value="'.$categoria["idCb"].'">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon text-red">
                                                        <i class="fas fa-tag"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-uppercase eValidarC" name="eNombreC" id="eNombreC" value="'.$categoria["categoria"].'" placeholder="CATEGORÍA" required autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <textarea class="form-control eDesc" rows="5" name="eDesc" id="eDesc" style="resize: none;" placeholder="Descripción" required>'.$categoria["descripcion"].'</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-tags"></i>
                                                    </span>
                                                    <input type="text" class="form-control epClave tagsinput" name="epClave" id="epClave" value="'.$categoria["palabrasClave"].'" data-role="tagsinput" placeholder="Palabras clave (separadas por coma)" required>
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
                    throw new ModelsException('La categoría no existe.');
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
    public function editarCategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
        
            global $http;
            
            $id = intval($http->request->get("idC"));
            $idCb = intval($http->request->get("idCb"));

            $nombreC = $this->db->scape(trim($http->request->get('eNombreC')));
            if($nombreC == ""){
                throw new ModelsException('El campo Categoría está vacío.');
            }
            $nombreC = Helper\Strings::clean_string(mb_strtoupper($nombreC, 'UTF-8'));
            
            $categorias = $this->db->select('*', 'categorias', null, "categoria = '".$nombreC."' AND id != '$id'");
            if($categorias){
                throw new ModelsException('La categoría '.$nombreC.' ya existe en la base de datos.');
            }

            $rutaC = Helper\Strings::url_amigable(mb_strtolower($nombreC, 'UTF-8'));
            
            $desc = $this->db->scape($http->request->get('eDesc'));
            if($desc == ""){
                $desc = "Libros de ".$nombreC;
            }
            
            $pClave = $this->db->scape($http->request->get('epClave'));
            if($pClave == ""){
                $pClave = "Libros de ".$nombreC;
            }
            
            $oferta = intval($http->request->get('eOferta'));
            $oPrecio = (real) $http->request->get('eoPrecio');
            $oPorcentaje = (real) $http->request->get('eoPorcentaje');
            $oFecha = $this->db->scape($http->request->get('eoFecha'));
            
            $categoria = $this->categoria($id);
            if(!$categoria){
                throw new ModelsException("La categoría no existe");
            }
            if($id==1){
            	if($categoria["categoria"] != $nombreC){
            	    throw new ModelsException("El nombre de está categoría no puede ser editada.");
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
                ), "idCategoria='$id'"); 
                
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
                $producto = $p->productosPor('idCategoria',$id);

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
                                ), "id='$idProducto' AND idCategoria='$id'",1);
                            }else{
                                $this->db->update('productos', array(
                                    'ofertadoPorCategoria' => $oferta,
                                    'ofertadoPorSubcategoria' => 0,
                                    'ofertadoPorEditorial' => 0,
                        		    'ofertadoPorAutor' => 0,
                                    'oferta' => 0,
                                    'precioOferta' => $precioProducto,
                                    'descuentoOferta' => $porcentajeProducto,
                                    'finOferta' => $oFecha
                                ), "id='$idProducto' AND idCategoria='$id'",1);
                                
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


            if($categoria["categoria"] != $nombreC){
                # Actualizar banner
                $b = new Model\Banners;
                $banner = $b->bannerPor('ruta',$categoria["ruta"]);
                if($banner){
                    
                    $directorio = self::URL_ASSETS_WEBSITE.$banner["imagen"];
                    if($directorio != self::URL_ASSETS_WEBSITE.'assets/plantilla/img/banner/default/default.jpg' && file_exists($directorio)){
                        $infoFile = pathinfo($directorio);
                        $extension = $infoFile['extension'];
                        rename($directorio, self::URL_ASSETS_WEBSITE."assets/plantilla/img/banner/".$rutaC.'.'.$extension);
                        $urlBanner = "assets/plantilla/img/banner/".$rutaC.'.'.$extension;
                    }else{
                        $urlBanner = $banner["imagen"];
                    }

                    $actualizarBanner = $this->db->update('anuncios',array(
                        'ruta' => $rutaC,
                        'imagen' => $urlBanner
                    ), "ruta='".$categoria["ruta"]."'", 1);
                }
            }

            # Actualizar cabeceras
            $this->db->update('cabeceras', array(
                'ruta' => $rutaC,
                'titulo' => $nombreC,
                'descripcion' => $desc,
                'palabrasClave' => $pClave
            ), "id='$idCb'");

            # Actualizar categoría
            $this->db->update('categorias', array(
                'categoria' => $nombreC,
                'ruta' => $rutaC,
                'oferta' => $oferta,
                'precioOferta' => $precio,
                'descuentoOferta' => $porcentaje,
                'finOferta' => $oFecha
            ), "id='$id'");

            # Actualizar subcategoría
            $this->db->update('subcategorias', array(
                'ofertaCategoria' => $oferta,
                'oferta' => 0,
                'precioOferta' => $precio,
                'descuentoOferta' => $porcentaje,
                'finOferta' => $oFecha
            ), "id_categoria='$id'");

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

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de categoría '.$id.' '.$nombreC, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

            if($oferta==1){
                $ofertaTexto = ($oPrecio==0) ? "a -".$oPorcentaje."%" : 'todo a $'.number_format($oPrecio,2);
                (new Model\Actividad)->registrarActividad('Notificacion', "Adición de oferta ($ofertaTexto) en categoría $id ".$nombreC." con vigencia hasta $oFecha", $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La categoría '.$id.' '.$nombreC.' se editó correctamente.');

        } catch (ModelsException $e) {
            
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }

    }


    /**
     * Elimina una categoría 
     * 
     * @return array
    */ 
    public function eliminarCategoria() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
        
            global $http;
            $id = intval($http->request->get('id'));
            if($id==1){throw new ModelsException("Está categoría no puede ser eliminada.");}
            // Traer dato de la categoría por id
            $categoria = $this->categoria($id);
            if(!$categoria){
                throw new ModelsException('La categoría no existe.');
            }else{
                $sb = new Model\Subcategorias;
                $subcategorias = $sb->subcategoriasPor('id_categoria', $id);

                $b = new Model\Banners;
                $banner = $b->bannerPor('ruta',$categoria["ruta"]);

                if($subcategorias){
                    return array('status' => 'info', 'title' => '¡Atención!','message' => 'La categoría no puede ser eliminada ya que existen registros de subcategorías asociadas a está.');
                }else{

                    $p = new Model\Productos;
                    $productos = $p->ProductosPor('idCategoria', $id);
                    if($productos){
                        return array('status' => 'info', 'title' => '¡Atención!','message' => 'La categoría no puede ser eliminada ya que existen registros de productos asociados a está.');
                    }

                    $rutaPortada = self::URL_ASSETS_WEBSITE.$categoria["portada"];
                    $rutaOferta = self::URL_ASSETS_WEBSITE.$categoria["imgOferta"];
                    $rutaBanner = self::URL_ASSETS_WEBSITE.$banner["imagen"];

                    if($categoria["portada"] != "assets/plantilla/img/cabeceras/default/default.jpg"){
                        unlink($rutaPortada);
                    }
                    if($categoria["oferta"] == 1){
                        if($categoria["imgOferta"] != "assets/plantilla/img/ofertas/default/default.jpg"){
                            unlink($rutaOferta);
                        }
                    }
                    if($banner["imagen"] != "" && $banner["imagen"] != "assets/plantilla/img/banner/default/default.jpg"){
                        unlink($rutaBanner);
                    }
                    $eliminarCategoria = $this->db->delete('categorias', "id='$id'", 1);
                    $eliminarCabeceras = $this->db->delete('cabeceras', "id='".$categoria["idCb"]."'", 1);
                    $eliminarBanner = $this->db->delete('anuncios', "ruta='".$categoria["ruta"]."'", 1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de categoría '.$id, $perfil, $administrador['id_user'], 4, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'La categoría ha sido eliminada.');
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
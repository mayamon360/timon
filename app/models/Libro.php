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
 * Modelo Libro
 */
class Libro extends Models implements IModels {
    use DBModel;
    
    /**
     * Tarjeta HTML para mostrar datos de libro en un div de 5 columnas
     * 
     * Autor(cargarLibrosAutor), Busqueda(realizarBusqueda), Destacados(cargarNuevos,cargarMasvendidos), Editorial(cargarLibroseditorial), Libros(cargarLibros)=>*aplica en categorias y subcategorias 
     * 
     * $ruta        : href      enlace
     * $imagen      : src       url de la imagen
     * $nuevo       : html span 'NUEVO'  | ''
     * $oferta      : html span 'OFERTA' | ''
     * 
     * $posicion    : html span 'TOP #'  | ''
     * 
     * $producto    : string    nombre del producto
     * $autores     : html a    autores
     * $precio      : html div  '$ 0.00'
     * $id_producto : int
     * $stock       : int
     * 
     * @return string
    */
    public function libroHtml($ruta,$imagen,$nuevo,$oferta,$posicion,$producto,$autores,$precio,$id_producto,$stock) {
        
        global $config;
        
        $html = '';
        $html .= '
        <div class="col-6 col-sm-6 col-md-3 col-lg-five px-5 px-sm-2 py-2 padding-10">
            '.$nuevo.'
                <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                    <a href="'.$ruta.'">
                        <img class="card-img" src="'.$imagen.'?'.time().'">
                        '.$oferta.'
                        '.$posicion.'
                    </a>
                </div>
                <span class="d-block mt-3 px-3">
                    <a class="text-truncate enlace_negro" href="'.$ruta.'">'.$producto.'</a>
                </span>
                <span class="d-block">
                    <p class="text-truncate m-0 px-3 autor">'.$autores.'</p>
                </span>
                <div class="row flex align-items-center my-2 precio">
                    '.$precio.'
                </div>
                <!--
                <div class="card-footer px-3 py-2">
                    <div class="row d-flex align-items-center">
                        <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$id_producto.'">';
                            if($this->id_user === null){
                                $html .= '<a href="'.$config['build']['url'].'autenticacion" class="love" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                            }else{
                                $stmt = $this->db->select("*","deseos",null,"idUsuario='".$this->id_user."' AND idProducto='$id_producto'",1);
                                if($stmt){
                                    $html .= '<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>';
                                }else{
                                    $html .= '<a href="#" class="love agregar_deseo" id="'.$id_producto.'" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                }
                            }
                        $html .= '
                        </div>
                        <div class="col-9 pl-1 text-right">';
                            if($stock > 0){
                                $html .= '
                                <button type="button" idLibro="'.$id_producto.'" class="btn btn-sm btn-icon boton_color agregar_carrito">
                                    <span class="btn-inner--text">Agregar</span>
                                    <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
                                </button>';
                            }
                        $html .= '    
                        </div>
                    </div>                                                                                                                 
                </div>
                -->
            </div>
        </div>';
        
        return $html;
        
    }
    
    /**
     * Tarjeta HTML para mostrar datos de libro en un slide, en la parte de abajo de infoproducto
     * 
     * Libros(librosSlideEditorial, librosSlideCategoria, librosSlideSubcategoria)
     * 
     * $ruta        : href      enlace
     * $imagen      : src       url de la imagen
     * $nuevo       : html span 'NUEVO'  | ''
     * $oferta      : html span 'OFERTA' | ''
     * $producto    : string    nombre del producto
     * $autores     : html a    autores
     * $precio      : html div  '$ 0.00'
     * $id_producto : int
     * $stock       : int
     * 
     * @return string
    */
    public function slideLibroHtml($ruta,$imagen,$nuevo,$oferta,$producto,$autores,$precio,$id_producto,$stock) {
        
        global $config;
        
        $html = '';
        $html .= $nuevo.'
            <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                <a href="'.$ruta.'" class="pixelLibro">
                    <img class="card-img" src="'.$imagen.'?'.time().'">
                    '.$oferta.'
                </a>
            </div>
            <span class="d-block mt-3 px-3">
                <a class="text-truncate pixelLibro enlace_negro" href="'.$ruta.'">'.$producto.'</a>
            </span>
            <span class="d-block">
                <p class="text-truncate m-0 autor">'.$autores.'</p>
            </span>
            <div class="row flex align-items-center my-2 precio">
                '.$precio.'
            </div>
            <!--<div class="card-footer px-3 py-2">
                <div class="row d-flex align-items-center">
                    <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$id_producto.'">';
                        if($this->id_user === null){
                            $html .= '<a href="'.$config['build']['url'].'autenticacion" class="love" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                        }else{
                            $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto='$id_producto'",1);
                            if($stmt){
                                $html .= '<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>';
                            }else{
                                $html .= '<a href="#" class="love agregar_deseo" id="'.$id_producto.'" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                            }
                        }
                    $html .= '
                    </div>
                    <div class="col-9 pl-1 text-right">';
                    if($stock > 0){
                    $html .= '
                        <button type="button" idLibro="'.$id_producto.'" class="btn btn-sm btn-icon boton_color agregar_carrito">
                            <span class="btn-inner--text d-none d-sm-inline">Agregar</span>
                            <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
                        </button>';
                    }
                    $html .= '    
                    </div>
                </div>                                                                                                                 
            </div>-->
        </div>';
        
        return $html;
        
    }
    
    /**
     * Tarjeta HTML para mostrar datos de libro en un slide
     * 
     * Libros(librosSlideEditorial,)
     * 
     * $ruta        : href      enlace
     * $imagen      : src       url de la imagen
     * $nuevo       : html span 'NUEVO'  | ''
     * $oferta      : html span 'OFERTA' | ''
     * $producto    : string    nombre del producto
     * $autores     : html a    autores
     * $precio      : html div  '$ 0.00'
     * $id_producto : int
     * $stock       : int
     * 
     * @return string
    */
    public function deseoLibroHtml($ruta,$imagen,$nuevo,$oferta,$producto,$autores,$precio,$id_producto,$stock) {
        
        global $config;
        
        $html = '';
        $html .= '
        <div class="col-12 col-sm-6 col-md-3 col-lg-five px-5 px-sm-2 py-2 padding-10" id="deseo'.$id_producto.'">
            '.$nuevo.'
                <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                    <a href="'.$ruta.'">
                        <img class="card-img" src="'.$imagen.'?'.time().'">
                        '.$oferta.'
                    </a>
                </div>
                <span class="d-block mt-3 px-3">
                    <a class="text-truncate enlace_negro" href="'.$ruta.'">'.$producto.'</a>
                </span>
                <span class="d-block">
                    <p class="text-truncate m-0 px-3 autor">'.$autores.'</p>
                </span>
                <div class="row flex align-items-center my-2 precio">
                    '.$precio.'
                </div>
                <div class="card-footer px-3 py-2">
                    <div class="row d-flex align-items-center">
                        <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$id_producto.'">
                            <a href="#" class="text-red eliminar_deseo" id="'.$id_producto.'" data-toggle="tooltip" data-html="true" title="<small><b>Eliminar de mi lista de deseos</b></small>">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                        <div class="col-9 pl-1 text-right">';
                    if($stock > 0){
                    $html .= '
                        <button type="button" idLibro="'.$id_producto.'" class="btn btn-sm btn-icon boton_color agregar_carrito">
                            <span class="btn-inner--text">Agregar</span>
                            <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
                        </button>';
                    }
                    $html .= '    
                    </div>
                    </div>                                                                                                                 
                </div>
            </div>
        </div>';
        
        return $html;
        
    }
    
    /**
     * Obtiene libro aleatorio
     *
     * @return false|array con la informacion del libro
     */   
    public function libroRand() {

        global $config;
        $mi_libro = $this->libroPor('*', null, "estado = 1 AND imagen != 'assets/plantilla/img/productos/default/default.jpg'", 1, "ORDER BY RAND()");
        $card = "";

        if($mi_libro){

            $intervalo = $this->libroNuevo($mi_libro);
            $nuevo = ($intervalo <= 30) ? '<span class="badge badge-lg badge-pill badge-nuevo z-depth-1">NUEVO</span>': '';

            $libro_precio_oferta = $this->libroPrecioOferta($mi_libro);
            $precio = $libro_precio_oferta[0];
            $oferta = $libro_precio_oferta[1];
            
            $id_producto = $mi_libro['id'];
            $producto = $mi_libro['producto'];
            $leyenda = $mi_libro['leyenda'];
            $autores = (new Model\Autor)->autoresHtml($mi_libro['autores']);
            $ruta = $config['build']['url'].'libro/'.$mi_libro['ruta'];
            $imagen = $config['build']['url'].$mi_libro['imagen'];
            $stock = $mi_libro['stock'];
            
            $card .= '
            <div class="card text-center ml-3 mr-3 mt-4 tarjeta_libro" data-label="In Progress">
                <div class="tarjeta_libro_imagen z-depth-3 mx-4">
                    <a href="'.$ruta.'" class="pixelLibro">
                        <img class="card-img" src="'.$imagen.'?'.time().'">
                        '.$nuevo.'
                        '.$oferta.'
                    </a>
                </div>
                <span class="d-block mt-3 px-3">
                    <a class="text-truncate pixelLibro enlace_negro" href="'.$ruta.'">'.$producto.' '.$leyenda.'</a>
                </span>
                <span class="d-block">
                    <p class="text-truncate m-0 autor">'.$autores.'</p>
                </span>
                <div class="row flex align-items-center my-2 precio">
                    '.$precio.'
                </div>
                <!--
                <div class="card-footer px-3 py-2">
                    <div class="row d-flex align-items-center">
                        <div class="col-3 pr-1 card-icon-actions-lg text-left div_deseo'.$id_producto.'">';
                            if($this->id_user === null){
                                $card .= '<a href="'.$config['build']['url'].'autenticacion" class="love" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                            }else{
                                $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto='$id_producto'",1);
                                if($stmt){
                                    $card .= '<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>';
                                }else{
                                    $card .= '<a href="#" class="love agregar_deseo" id="'.$id_producto.'" data-toggle="tooltip" data-html="true" title="<small><b>Añadir a mi lista de deseos</b></small>"><i class="far fa-heart"></i></a>';
                                }
                            }
                        $card .= '
                        </div>
                        <div class="col-9 pl-1 text-right">';
                            if($stock > 0){
	                        $card .= '
	                        <button type="button" idLibro="'.$id_producto.'" class="btn btn-sm btn-icon boton_color agregar_carrito">
	                            <span class="btn-inner--text">Agregar</span>
	                            <span class="btn-inner--icon"><i class="fas fa-shopping-bag"></i></span>
	                        </button>';
	                    }
                        $card .= '    
                        </div>
                    </div>                                                                                                                 
                </div>
                -->
            </div>';

        }

        return $card;

    }
    
    /**
     * Lista de libros segun opciones
     * 
     * this, Compra(agregarCarrito,cargarCompra,modificarCantidad)
     * 
     * @return array | false
    */
    public function libroPor($fields = '*', $inners = null, $where = null, $limit = null, $extra = ''){

        $stmt = $stmt = $this->db->select($fields,'productos', $inners, $where, $limit, $extra);
        if($stmt){
            return $stmt[0];
        }
        return false;

    }
    
    /**
     * Intervalo de tiempo por la fecha de registro
     * 
     * this, Autor, Busqueda, Destacados(slideNovedades,slideMasVendidos,cargarMasvendidos), Editorial, Libros(librosSlideEditorial,librosSlideCategoria,librosSlideSubcategoria,cargarLibros,cargarDeseos)
     * 
     * $mi_libro : array    libro
     * 
     * @return int
    */
    public function libroNuevo($mi_libro){
        
        $fecha_registro = date_create($mi_libro['fechaRegistro']);
        $intervalo = Helper\Strings::date_difference( date_format($fecha_registro, 'd-m-Y'), date('d-m-Y') );
        return $intervalo;

    }
    
    /**
     * Informacion del precio con oferta
     * 
     * this, Autor, Busqueda, Destacados(slideNovedades,slideMasVendidos,cargarNuevos,cargarMasvendidos), Editorial, Libros(librosSlideEditorial,librosSlideCategoria,librosSlideSubcategoria,cargarLibros,cargarDeseos)
     * 
     * $mi_libro : array    libro
     * 
     * @return array
     * 
    */
    public function libroPrecioOferta($mi_libro){

        if ($mi_libro['ofertadoPorCategoria'] == 1 || $mi_libro['ofertadoPorSubcategoria'] == 1 || $mi_libro['ofertadoPorEditorial'] == 1 || $mi_libro['ofertadoPorAutor'] == 1 || $mi_libro['oferta'] == 1){
            $oferta = '<span class="badge badge-lg badge-pill badge-oferta z-depth-1">OFERTA</span>';
            $precio_tarjeta = '<div class="col-6 text-right px-0 precio_dto">
                                <s>$'.number_format($mi_libro['precio'],2).'</s>
                            </div>
                            <div class="col-6 text-left px-0 precio_normal">
                                $'.number_format($mi_libro['precioOferta'],2).'
                            </div>';
            $precio_info_producto = '<div class="col-12 text-center pt-2 precio_dto">
                                        <s>$'.number_format($mi_libro['precio'],2).'</s>
                                    </div>
                                    <div class="col-12 text-center pb-2 precio_normal">
                                        $'.number_format($mi_libro['precioOferta'],2).'
                                    </div>';
        }else{
            $oferta = '';
            $precio_tarjeta = '<div class="col-12 text-center px-3 precio_normal">$'.number_format($mi_libro['precio'],2).'</div>';
            $precio_info_producto = '<div class="col-12 text-center pt-2 precio_normal">
                                        $'.number_format($mi_libro['precio'],2).'
                                    </div>';
        }

        return array($precio_tarjeta, $oferta, $precio_info_producto);
    }
    
    /**
     * Informacion de la disponibilidad de stock
     * 
     * Autor, Busqueda, Destacados(slideNovedades,slideMasVendidos,cargarNuevos,cargarMasvendidos), Editorial, Libros(librosSlideEditorial,librosSlideCategoria,librosSlideSubcategoria,cargarLibros,cargarDeseos)
     * 
     * $mi_libro : array    libro
     * 
     * @return array
     * 
    */
    public function disponibilidad(int $stock = 0){
        if($stock == 0){
            return '<div class="col-12 bg-white py-2 border-top rounded-bottom disponibilidad_libro">
                <span class="badge badge-md badge-pill bg-red text-white" style="font-size:var(--fontSize-10);">No disponible</span>
                <br>
                <span class="badge badge-dot mt-3 animated infinite flash">
                    <i class="bg-warning"></i> No es libro digital
                </span>
            </div>';
        }elseif($stock > 0 && $stock <= 3){
            return '<div class="col-12 bg-white py-2 border-top rounded-bottom disponibilidad_libro">
                <span class="badge badge-md badge-pill bg-green text-white" style="font-size:var(--fontSize-10);">Disponible</span>
                <p class="mb-0 mt-2 font-weight-normal" style="font-size:var(--fontSize-12);">Sujeto a disponibilidad</p>
                <p class="mb-1">Antes de realizar su compra confirme la existencia por teléfono</p>
                <span class="badge badge-dot mt-3 animated infinite flash">
                    <i class="bg-warning"></i> No es libro digital
                </span>
            </div>';
        }elseif($stock > 3){
            return '<div class="col-12 bg-white py-2 border-top rounded-bottom disponibilidad_libro">
                <span class="badge badge-md badge-pill bg-green text-white" style="font-size:var(--fontSize-10);">Disponible</span>
                <p class="mb-1">Antes de realizar su compra confirme la existencia por teléfono</p>
                <span class="badge badge-dot mt-3 animated infinite flash">
                    <i class="bg-warning"></i> No es libro digital
                </span>
            </div>';
        }
    }

    public function detalles($detalles){
        $detalles = json_decode($detalles, true);
        $esMulti = false;
        foreach ($detalles as $k => $v) {
            if(is_array($v)){
                $esMulti = true;
            }
        }
        $tr_detalles = "";
        $td_detalles = "";
        
        foreach ($detalles as $key => $value) {
            if(is_array($value)){
                $tr_detalles .= '<th scope="col" class="py-1 px-2 detalle">'.$key.'</th>';
                foreach ($value as $key2 => $value2) {
                    $td_detalles .= '<td class="py-1 px-2">'.$value2.'</td>';
                }
            }else if($value != null){
                $tr_detalles .= '<th scope="col" class="py-1 detalle">'.$key.'</th>';
                $td_detalles .= '<td class="py-1 px-2">'.$value.'</td>';
            }
        } 
        return array($tr_detalles, $td_detalles); 
    }

    public function guardarDeseo(){
        try {

            if($this->id_user === null){
                return array('status' => 'warning', 'title' => '¡Sesión caducada!', 'message' => 'Espera un momento, por favor...');
            }

            global $http;

            $id = intval($http->query->get('id'));
            if($id == 0){
                throw new ModelsException('Es necesario seleccionar un libro para añadir a tu lista de deseos.');
            }

            $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto=$id",1);
            if($stmt){
                throw new ModelsException('El libro ya se encuentra agregado a tu lista de deseos.');
            }

            $this->db->insert('deseos', array(
                'idUsuario' => $this->id_user,
                'idProducto' => $id
            ));

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El libro se agregó a tu lista de deseos.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }

    public function validarDeseo($id){
        $stmt = $this->db->select("*", "deseos", null, "idUsuario = {$this->id_user} AND idProducto = $id", 1);
        return $stmt;
    }

    public function eliminarDeseo(){
        try {
            
            if($this->id_user === null){
                return array('status' => 'warning', 'title' => '¡Sesión caducada!', 'message' => 'Espera un momento, por favor...');
            }

            global $http;

            $id = intval($http->query->get('id'));
            if($id == 0){
                throw new ModelsException('Es necesario seleccionar el libro a eliminar de tu lista de deseos.');
            }

            $stmt = $this->db->select("*","deseos",null,"idUsuario=$this->id_user AND idProducto=$id",1);
            if(!$stmt){
                throw new ModelsException('El libro no se encuentra en tu lista de deseos.');
            }

            $this->db->delete('deseos',"idUsuario='{$this->id_user}' AND idProducto='$id'",1);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El libro se eliminó de tu lista de deseos.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
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
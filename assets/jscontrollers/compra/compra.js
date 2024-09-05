$(".input_cantidad").inputmask("9{1,2}",{ 
    "placeholder": "0",
    "rightAlign": false,
    "oncleared": function(){ 
        $(this).val('');  
    }
});

function cargarCompra(){
    $.ajax({
        type : 'GET',
        url : 'api/cargarCompra',
        dataType: 'json',
        beforeSend: function(){
            $(".informacion_compra").addClass('d-none');
            $(".cargando").removeClass('d-none');
        },
        success : function(json) {
            $('.resultados').html(json.resultados);
            $('.tabla_carrito').html(json.content);
            $('.div_carrito_movil').html(json.content_movil);
            $('.subtotal').html(json.subtotal);
            $('.opc_envio').html(json.opc_envio);
            $('.info_dir').html(json.info_dir);
            $('.total').html(json.total);
            $('.cantidad_carrito').html(json.cantidad);
            if(json.status == 'empty'){
                $('.limpiar_carrito').addClass('d-none');
                $('.tabla_carrito').removeClass('table-hover');
                $('.div_procesar_compra').html(json.boton_procesar);
            }else if(json.status == 'full'){
                $('.limpiar_carrito').removeClass('d-none');
                $('.tabla_carrito').addClass('table-hover');
                $('.div_procesar_compra').html(json.boton_procesar);
            }
        },
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador 1.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        },
        complete : function() {
            $(".eliminar_carrito").on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr("idLibro");
                elimiarCarrito(id);
            });
            $(".informacion_compra").removeClass('d-none');
            $(".cargando").addClass('d-none');
        }
    })
}
cargarCompra();

function elimiarCarrito(id){
    $.ajax({
        type : 'GET',
        url : 'api/elimiarCarrito',
        dataType: 'json',
        data : {id:id},
        success : function(json) {
            if(json.status == 'error'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: null,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        }
                    }
                })
            }
        },
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador 2.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        },
        complete : function() {
            cargarCompra();
        }
    })
}

function modificarCantidad(cantidad,id){
    if(cantidad == 0 || cantidad == ''){
        cantidad = 1;
        $('#input_cantidad'+id).val(cantidad);
    }
    $.ajax({
        type : 'GET',
        url : 'api/modificarCantidad',
        dataType: 'json',
        data : {cantidad:cantidad,id:id},
        success:function(json){
            if(json.status == 'error'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: true,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        }
                    }
                })
            }
        },
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador 3.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        },
        complete : function() {
            cargarCompra();
        }
    })
}
$(document).on('keypress', '.input_cantidad', function(e){
    e.defaultPrevented;
    var cantidad = $(this).val();
    var id = $(this).attr('idLibro');
    if(e.which == 13) {
        modificarCantidad(cantidad,id);
        return false;
    }
});
$(document).on('blur', '.input_cantidad', function(e){
    e.defaultPrevented;
    var cantidad = $(this).val();
    var id = $(this).attr('idLibro');
    modificarCantidad(cantidad,id);
});
$(document).on('click', '.quitar_cantidad', function(){
    var id = $(this).attr('idLibro');
    var cantidad = parseInt($('#input_cantidad'+id).val());
    var n_cantidad = cantidad-1;
    if(n_cantidad > 0){
        modificarCantidad(n_cantidad,id);
    }
});
$(document).on('click', '.agregar_cantidad', function(){
    var id = $(this).attr('idLibro');
    var cantidad = parseInt($('#input_cantidad'+id).val());
    var n_cantidad = cantidad+1;
    modificarCantidad(n_cantidad,id);
});

$(".limpiar_carrito").on('click', function() {
    $.ajax({
        type : 'GET',
        url : 'api/limpiarCarrito',
        dataType: 'json',
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador 4.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        },
        complete : function() {
            cargarCompra();
        }
    })
});

		 
$(document).on('input', '.metodo_envio', function(){
    $.ajax({
        type : 'GET',
        url : 'api/metodoEnvio',
        dataType: 'json',
        data : {metodo_envio:$(this).val()},
        success:function(json){
            if(json.status == 'error'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: true,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        }
                    }
                })
            }
        },
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador 5.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        },
        complete : function() {
            cargarCompra();
        }
    })
})







/* JS REGISTRAR COMPRA */

// DOM
$(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $("#rfc").inputmask({"mask": "A{3,4}-999999-***"});
    $("#telefono").inputmask({"mask": "(999) 999 9999"});
});
// DOM

// Resetear el formulario
setTimeout(function (){
    limpiarBusqueda();
}, 100);
// Resetear el formulario 

// Función para cargar la lista de productos en compra
function cargarListaCompras(){
    $.ajax({
        type : "POST",
        url : "api/cargarListaCompras",
        dataType: 'json',
        beforeSend: function(){ 
            $("#listaProductosCompra").html('<td colspan="7" style="vertical-align:middle;"><p class="text-center" style="margin:0; padding:10px 0px;">Procesando, por favor espere...</p></td>');
        },
        success:function(json){
            $(".divResultadosBusquedaC").addClass('hidden');
            $("#listaProductosCompra").html(json.tbody);
            $("#sumaCantidad").html(json.sumaCantidad);
            $("#totalTitulo").html(json.total);
            $("#total").html(json.total);
            $("#totalSuperior").html(json.total);
            $("#totalCantSuperior").html(json.sumaCantidad);
            if(json.status == 'llena'){
                $(".divLimpiarListaCompra").removeClass('hidden');
                $(".divConfirmarCompra").removeClass('hidden');
                $("#btnModalConfirmarCompra").removeClass('hidden');
            }else if(json.status == 'vacia'){
                $(".divLimpiarListaCompra").addClass('hidden');
                $(".divConfirmarCompra").addClass('hidden');
                $("#btnModalConfirmarCompra").addClass('hidden');
            }
            $(".inputCantidad").inputmask("9{1,5}",{ 
                "placeholder": "0",
                "rightAlign": false,
                "oncleared": function(){ 
                    $(this).val('');  
                }
            }); 
            $(".inputCosto").number(true,2);
            $(".inputPrecio").number(true,2);
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar cargar la lista de entradas.');
        },
        complete: function(){
            $('[data-toggle="tooltip"]').tooltip();
            $("#tdProcesando").addClass('hidden');
        }
    })
}
cargarListaCompras();
// Función para cargar la lista de productos en compra

// Función para obtener los proveedores
function obtenerProveedores(seleccionado){
    var metodo = 'cargar proveedores';
    $.ajax({
        type : "POST",
        url : "api/obtenerProveedores",
        dataType: 'json',
        data : {metodo:metodo,seleccionado:seleccionado},
        success:function(json){
            $("#seleccionar_proveedor").html(json.proveedores);
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar cargar los proveedores');
        },
        complete: function(){
            $('.seleccionar').select2();
        }
    });
}
obtenerProveedores(0);
// Función para obtener los proveedores

// Evento para validar que el nombre del nuevo proveedor no exista
$(document).on('change', '.validar', function(){
    var item = $(this).attr('tipo');
    var valor = $(this).val();
    var id = $(this).attr("key");
    $.ajax({
        url:"api/validarProveedor",
        method:'POST',
        dataType: 'json',
        data : {item:item,valor:valor,id:id},
        success:function(json){
            if(json.status == 'error'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                         }
                    }
                })
                .then((value) => {
                    if(value){
                        $("#"+item).val("");
                        $("#"+item).focus();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar validar el nombre del nuevo proveedor');
        }
    })
});
// Evento para validar que el nombre del nuevo proveedor no exista

// Función para agregar nuevo proveedor
function agregarProveedor(){
    var $ocrendForm = $(this), __data = {};
    $('#agregar_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 
    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/agregarProveedor",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true);
                $("#enviar").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalAgregar').modal('hide');
                    obtenerProveedores(json.agregado);
                    $.notify({
                        icon: 'fas fa-check',
                        title: '<strong>'+json.title+'</strong>',
                        message: json.message,
                    }, {
                        position: 'fixed',
                        type: 'success',
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 1000,
                        delay: 1000,
                        timer: 500,
                        mouse_over: "pause",
                        animate: {
                            enter: 'animated bounceIn',
                            exit: 'animated bounceIn'
                        }
                    });
                } else {
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "error",
                        closeOnClickOutside: false,
                        closeOnEsc: true,
                        buttons: {
                            cancel: {
                                text: "Cerrar",
                                value: true,
                                visible: true,
                                className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                                closeModal: true,
                            }
                        }
                    });
                }
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno al intentar agregar un nuevo proveedor.');
            },
            complete: function(){ 
                $ocrendForm.data('locked', false);
                $("#enviar").removeAttr("disabled");
            } 
        });
    }
} 
$('#enviar').click(function(e) {
    e.defaultPrevented;
    agregarProveedor();
});
// Función para agregar nuevo proveedor

// Función para buscar producto por código o nombre
function buscarProductoCompra(){
    var busqueda = $("input#busqueda").val();
    $.ajax({
        type : "POST",
        url : "api/buscarProductoCompra",
        dataType: 'json',
        data : {busqueda:busqueda},
        beforeSend: function(){ 
            $("input#busqueda").attr('disabled','disabled');
            $("#diProcesando").removeClass('hidden');
        },
        success : function(json) {
            if(json.status == ''){

                $(".divResultadosBusquedaC").removeClass('hidden');
                $("table.resultadosBusquedaC tbody").html(json.tr);

                $("button#buscarProducto").addClass('hidden');
                $("button#limpiarB").removeClass('hidden');

            } else if(json.status == 'success') {

                limpiarBusqueda();
                cargarListaCompras();
                $.notify({
                    icon: 'fas fa-check',
                    title: '<strong>'+json.title+'</strong>',
                    message: json.message,
                }, {
                    position: 'fixed',
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    z_index: 1000,
                    delay: 1000,
                    timer: 500,
                    mouse_over: "pause",
                    animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated fadeOut'
                    }
                });

            } else if(json.status == 'error') {

                $(".divResultadosBusquedaC").addClass('hidden');
                $("table.resultadosBusquedaC tbody").html('');
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        limpiarBusqueda();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar buscar el producto.');
        },
        complete: function(){ 
            $("input#busqueda").removeAttr('disabled');
            $("input#busqueda").val('');
            $("input#busqueda").focus();
            $("#diProcesando").addClass('hidden');
            $('[data-toggle="tooltip"]').tooltip();
        } 
    });
} 
$(document).on('keypress', '#buscador input#busqueda', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
    	if($(this).val() != ''){
            buscarProductoCompra();
        }
        return false;
    }
});
$('#buscarProducto').click(function(e) {
    if($('input#busqueda').val() != ''){
    	buscarProductoCompra();
    }
});
// Función para buscar producto por código o nombre

// Función para limpiar la búsqueda
$('#limpiarB').click(function(e) {
    limpiarBusqueda();
});
function limpiarBusqueda(){
    $("button#buscarProducto").removeClass('hidden');
    $("button#limpiarB").addClass('hidden');

    $("input#busqueda").val('');
    $("input#busqueda").focus();

    $(".divResultadosBusquedaC").addClass('hidden');
    $("table.resultadosBusquedaC tbody").html('');
}
// Función para limpiar la búsqueda

// Evento para agregar productos a la lista al hacer clic en el botón en el modal de búsqueda de productos
$(document).on('click','#btnAgregar',function(e){
    var key = $(this).attr('key');
    $.ajax({
        type : "POST",
        url : "api/agregarProductoListaCompra",
        dataType: 'json',
        data : {key:key},
        success:function(json){
            if(json.status == 'success'){
                $('#modalProductos').modal('hide');
                cargarListaCompras();
                $.notify({
                    icon: 'fas fa-check',
                    title: '<strong>'+json.title+'</strong>',
                    message: json.message,
                }, {
                    position: 'fixed',
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    z_index: 1000,
                    delay: 1000,
                    timer: 500,
                    mouse_over: "pause",
                    animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated fadeOut'
                    }
                });
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){
            $("input#busqueda").val('');
            $("input#busqueda").focus();
            $(".divResultadosBusquedaC").addClass('hidden');
    	    $("table.resultadosBusquedaC tbody").html('');
            limpiarBusqueda();
        }
    });
});
// Evento para agregar productos a la lista al hacer clic en el botón en el modal de búsqueda de productos

// Evento para eliminar un producto de la lista al hacer clic en el botón eliminarProductoListaCompra
$(document).on('click', '.eliminarProductoListaCompra', function(){
    var key = $(this).attr('key');
    $.ajax({
        type : "POST",
        url : "api/quitarProductoListaCompra",
        dataType: 'json',
        data : {key:key},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCompras();
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        cargarListaCompras();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar eliminar el producto de la lista.');
        }
    });
});
// Evento para eliminar un producto de la lista al hacer clic en el botón eliminarProductoListaCompra


// Función para limpiar la lista de productos
function limpiarListaCompra() {
    $.ajax({
        type : "POST",
        url : "api/vaciarListaCompra",
        dataType: 'json',
        success:function(json){
            $(".divLimpiarListaCompra").addClass('hidden');
            $("input#busqueda").val('');
            $("input#busqueda").focus();
            cargarListaCompras();
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar limpiar la lista.');
        }
    }); 
}
$(document).on('click', '.limpiarListaCompra', function(){
    limpiarListaCompra();
});
// Función para limpiar la lista de productos

// Función para modificar la cantidad de algún producto en la lista
function modificarCantidad(cantidad, key) {
    if(cantidad == 0 || cantidad == ''){
        cantidad = 1;
        $('#inputCantidad'+key).val(cantidad);
    }
    $.ajax({
        type : "POST",
        url : "api/modificarCantidadCompra",
        dataType: 'json',
        data : {cantidad:cantidad,key:key},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCompras();
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        $("input#busqueda").focus();
                        cargarListaCompras();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar modificar la cantidad del producto en la lista.');
        }
    });
}
$(document).on('keypress', '.inputCantidad', function(e){
    e.defaultPrevented;
    var nuevaCantidad = $(this).val();
    var key = $(this).attr('key');
    if(e.which == 13) {
        modificarCantidad(nuevaCantidad, key);
        return false;
    }
});
$(document).on('blur', '.inputCantidad', function(e){
    e.defaultPrevented;
    var nuevaCantidad = $(this).val();
    var key = $(this).attr('key');
    modificarCantidad(nuevaCantidad, key);
});
$(document).on('click', '.btnQuitarCantidad', function(){
    var key = $(this).attr('key');
    var inputCantidad = parseInt($('#inputCantidad'+key).val());
    var nuevaCantidad = inputCantidad-1;
    if(nuevaCantidad > 0){
        modificarCantidad(nuevaCantidad, key);
    }
});
$(document).on('click', '.btnAgregarCantidad', function(){
    var key = $(this).attr('key');
    var inputCantidad = parseInt($('#inputCantidad'+key).val());
    var nuevaCantidad = inputCantidad+1;
    modificarCantidad(nuevaCantidad, key);
});
// Función para modificar la cantidad de algún producto en la lista

// Función para editar el precio del producto en la lista (los cambios afectan a la BD hasta que se confirme)
function modificarPrecio(key, precio, costo) {
    $.ajax({
        type : "POST",
        url : "api/modificarPrecioVenta",
        dataType: 'json',
        data : {key:key,precio:precio,costo:costo},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCompras();
                $.notify({
                    icon: 'fas fa-check',
                    title: '<strong>'+json.title+'</strong>',
                    message: json.message,
                }, {
                    position: 'fixed',
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    z_index: 1000,
                    delay: 1000,
                    timer: 500,
                    mouse_over: "pause",
                    animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated bounceOut'
                    }
                });
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        $("input#busqueda").focus();
                        cargarListaCompras();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar cambiar el precio del producto en la lista.');
        }
    });
}
$(document).on('keypress', '.inputPrecio', function(e){
    e.defaultPrevented;
    var key = $(this).attr('key');
    var precio = $('#inputPrecio'+key).val();
    var costo = $('#inputCosto'+key).val();
    $('#btnPrecio'+key).removeAttr('disabled');
    if(e.which == 13) {
        modificarPrecio(key, precio, costo);
        return false;
    }
})
$(document).on('click', '.btnPrecio', function(){
    var key = $(this).attr('key');
    var precio = $('#inputPrecio'+key).val();
    var costo = $('#inputCosto'+key).val();
    modificarPrecio(key, precio, costo);
})
// Función para editar el precio del producto en la lista (los cambios afectan a la BD hasta que se confirme)

// Función para editar el costo del producto en la lista (los cambios afectan a la BD hasta que se confirme)
function modificarCosto(key, costo, precio) {
    $.ajax({
        type : "POST",
        url : "api/modificarCostoCompra",
        dataType: 'json',
        data : {key:key,costo:costo,precio:precio},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCompras();
                $.notify({
                    icon: 'fas fa-check',
                    title: '<strong>'+json.title+'</strong>',
                    message: json.message,
                }, {
                    position: 'fixed',
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    z_index: 1000,
                    delay: 1000,
                    timer: 500,
                    mouse_over: "pause",
                    animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated bounceOut'
                    }
                });
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        $("input#busqueda").focus();
                        cargarListaCompras();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno al intentar cambiar el costo del producto en la lista.');
        }
    });
}
$(document).on('keypress', '.inputCosto', function(e){
    e.defaultPrevented;
    var key = $(this).attr('key');
    var costo = $('#inputCosto'+key).val();
    var precio = $('#inputPrecio'+key).val();
    $('#btnCosto'+key).removeAttr('disabled');
    if(e.which == 13) {
        modificarCosto(key, costo, precio);
        return false;
    }
})
$(document).on('click', '.btnCosto', function(){
    var key = $(this).attr('key');
    var costo = $('#inputCosto'+key).val();
    var precio = $('#inputPrecio'+key).val();
    modificarCosto(key, costo, precio);
})
// Función para editar el costo del producto en la lista (los cambios afectan a la BD hasta que se confirme)

// Función para confirmar las entradas de los productos como una compra
function confirmarCompra(){
    var proveedor = $("#seleccionar_proveedor").val();
    var factura = $("#factura").val();
    swal({
        title: "COMPRA",
        text: "¿Registrar la salida de productos como compra?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                closeModal: true,
            },
            confirm: {
                text: "Sí, continuar",
                value: true,
                visible: true,
                className: "btn btn-sm btn-flat btn-primary text-uppercase font-weight-bold",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if(value){
            $.ajax({
                type : "POST",
                url : "api/confirmarCompra",
                dataType: 'json',
                data : {proveedor:proveedor,factura:factura},
                beforeSend: function(){ 
                    $("#consignacion").addClass('hidden');
                    $("#ajuste").addClass('hidden');
                    $("#compra").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
                },
                success:function(json){
                    if(json.status == 'success'){
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "success",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            $('#seleccionar_proveedor').val('').trigger('change');
                            $('#factura').val('');
                            $('#pedido').val('');
                            limpiarListaCompra();
                            $('#modalConfirmarCompra').modal('hide');
                        });
                    }else{
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "error",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                confirm: {
                                    text: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-primary",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value){
                                cargarListaCompras();
                            }
                        });

                    }
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno al intentar registrar la compra.');
                },
                complete : function(){
                    $("#consignacion").removeClass('hidden');
                    $("#ajuste").removeClass('hidden');
                    $("#compra").html('<i class="fas fa-check"></i> Confirmar Compra').removeAttr('disabled').removeClass('disabled');
                }
            })
        }
    })
}
$(document).on('click', '#compra', function(e){
    confirmarCompra();
});
$(document).on('keypress', '#factura, #pedido', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        confirmarCompra();
        return false;
    }
});
// Función para confirmar las entradas de los productos como una compra

// Función para confirmar las entradas de los productos como una consignación de entrada
function consignacionEntrada(){
    var proveedor = $("#seleccionar_proveedor").val();
    var factura = $("#factura").val();
    swal({
        title: "ENTRADA DE CONSIGNACIÓN",
        text: "¿Registrar la entrada de consignación de los productos?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                closeModal: true,
            },
            confirm: {
                text: "Sí, continuar",
                value: true,
                visible: true,
                className: "btn btn-sm btn-flat btn-primary text-uppercase font-weight-bold",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if(value){
            $.ajax({
                type : "POST",
                url : "api/consignacionEntrada",
                dataType: 'json',
                data : {proveedor:proveedor,factura:factura},
                beforeSend: function(){ 
                    $("#ajuste").addClass('hidden');
                    $("#consignacion").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
                    $("#compra").addClass('hidden');
                },
                success : function(json) {
                    if(json.status == 'success'){
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "success",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            $('#seleccionar_proveedor').val('').trigger('change');
                            $('#factura').val('');
                            $('#pedido').val('');

                            limpiarListaCompra();
                            $('#modalConfirmarCompra').modal('hide');
                        });
                    }else{
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "error",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                                    closeModal: true,
                                }
                            }
                        })
                    }
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno al intentar registrar la entrada de consignación.');
                },
                complete : function(){
                    $("#ajuste").removeClass('hidden');
                    $("#consignacion").html('Entrada de consignación').removeAttr('disabled').removeClass('disabled');
                    $("#compra").removeClass('hidden');
                }
            });
        }
    })
}
$(document).on('click', '#consignacion', function(e){
    consignacionEntrada();
});
// Función para confirmar las entradas de los productos como una consignación de entrada

// Función para confirmar las entradas de los productos como un ajuste de inventario
function ajusteEntrada(){
    var accion = $("#seleccionar_proveedor").val();
    swal({
        title: "AJUSTE(SUMAR)",
        text: "¿Registrar la entrada de los productos como ajuste de inventario?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                closeModal: true,
            },
            confirm: {
                text: "Sí, continuar",
                value: true,
                visible: true,
                className: "btn btn-sm btn-flat btn-primary text-uppercase font-weight-bold",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if(value){
            $.ajax({
                type : "POST",
                url : "api/ajusteEntrada",
                dataType: 'json',
                data : {accion:accion},
                beforeSend: function(){ 
                    $("#ajuste").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
                    $("#consignacion").addClass('hidden');
                    $("#compra").addClass('hidden');
                },
                success : function(json) {
                    if(json.status == 'success'){
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "success",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            $('#seleccionar_proveedor').val('').trigger('change');
                            $('#factura').val('');
                            $('#pedido').val('');
                            limpiarListaCompra();
                            $('#modalConfirmarCompra').modal('hide');
                        });
                    }else{
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "error",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold btnCerrar",
                                    closeModal: true,
                                }
                            }
                        })
                    }
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno al intentar registrar el ajuste.');
                },
                complete : function(){
                    $("#ajuste").html('Ajuste de inventario (sumar)').removeAttr('disabled').removeClass('disabled');
                    $("#consignacion").removeClass('hidden');
                    $("#compra").removeClass('hidden');
                }
            });
        }
    })
}
$(document).on('click', '#ajuste', function(e){
    ajusteEntrada();
});
// Función para confirmar las entradas de los productos como un ajuste de inventario

// Eventos al abrir el modal de búsqueda de productos
$('#modalProductos').on('shown.bs.modal', function (e) {

    if (!$.fn.DataTable.isDataTable('#tablaProductosVer')) {

        // Clonar la fila thead
        $('#tablaProductosVer thead tr').clone(true).appendTo( '#tablaProductosVer thead' ).addClass("bg-gray");
        // Agregar cajas texto para filtros
        $('#tablaProductosVer thead tr:eq(1) th').each(function (i) {
            if(i == 3 || i == 4){
                var title = $(this).text();
                $(this).html( '<input class="form-control input-sm" type="text" placeholder="Filtrar por '+title+'" style="max-width:170px;"/>' );
                $('input',this).on('keyup change', function () {
                    if (dataTable.column(i).search() !== this.value) {
                        dataTable
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }else{
                $(this).html( '' );
            }
        });
        // Agregar cajas texto para filtros
    
    }else{
        $('#tablaProductosVer').DataTable().ajax.reload();
    }
    // Cargar los productos en la tabla
    var dataTable = $('#tablaProductosVer').DataTable({
        //"responsive": true,
        "scrollX": true,
        "order": [],   
        "orderCellsTop": true, 
        "ajax": {
            url: "api/mostrarProductosC",
            type: "POST",
            "complete": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "iDisplayLength" : 10, 
        "retrieve" : true,
        "deferRender": true,
        "processing" : true,
        "searchHighlight": true,
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
            },
            {
                "targets": [5,7],
                "className": "text-center font-weight-bold",
            },
            {
                "targets": 6,
                "className": "text-center",
            },
            {
                "targets": 8,
                "orderable": false,
            }
        ],
        "language": {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando lista de productos...",
            "processing": "Procesando...",
            "search": "BUSCAR PRODUCTOS:",
            "zeroRecords": "No se encontrarón resultados",
            "paginate": {
                "first": "<<",
                "last": ">>",
                "next": ">",
                "previous": "<"
            },
            "aria": {
                "sortAscending": ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        }
    });
    // Cargar los productos en la tabla
    $('#tablaProductosVer_filter input[type="search"]').focus();
})
// Eventos al abrir el modal de búsqueda de productos

// Eventos al cerrar el modal de búsqueda de productos
$('#modalProductos').on('hide.bs.modal hidden.bs.modal', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})
// Eventos al cerrar el modal de búsqueda de productos

$('#modalConfirmarCompra').on('hide.bs.modal hidden.bs.modal', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})

$('.btnCerrar .btnNota').on('click', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})





$("#precioCompra").number(true,2);
$("#precio").number(true,2);

function agregarProducto(){
    var formulario = $("#agregar_producto_form").serialize();
    $.ajax({
        url: "api/agregarProductoRapido",
    	method: 'POST',
    	dataType: 'json',
        data: formulario,
        success: function (json) {
            if(json.status == 'success'){
                $('#modalAgregarProducto').modal('hide');
                cargarListaCompras();
                $.notify({
                    icon: 'fas fa-check',
                    title: '<strong>'+json.title+'</strong>',
                    message: json.message,
                }, {
                    position: 'fixed',
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    z_index: 1000,
                    delay: 1000,
                    timer: 500,
                    mouse_over: "pause",
                    animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated fadeOut'
                    }
                });
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
            }
        },
    	error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
    	}
    })
}
$(document).on('keypress', '#codigoP', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        $("#nombreP").focus();
        return false;
    }
});
$(document).on('keypress', '#nombreP', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        $("#precioCompra").focus();
        return false;
    }
});
$(document).on('keypress', '#precioCompra', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        $("#precio").focus();
        return false;
    }
});
$(document).on('keypress', '#precio', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        agregarProducto();
        return false;
    }
});
$(document).on('click', '#registrarProducto', function(e){
    agregarProducto();
});

$('#modalAgregarProducto').on('hide.bs.modal hidden.bs.modal', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})
$('#modalAgregarProducto').on('show.bs.modal shown.bs.modal', function (e) {
    $("#agregar_producto_form")[0].reset();
    $(".alertaCodigo").addClass('hidden').html('');
    setTimeout(function (){
        $("#codigoP").focus();
    }, 100);
})

/* Validar que el codigo del producto no exista ya modal agregar */
$(document).on('change blur focusout', '.validarC', function () {
    codigo = $(this).val();
    producto = $(".validarP").val();
    $.ajax({
        url: "api/validarCodigoProducto",
        method: 'POST',
        dataType: 'json',
        data: { codigo: codigo, producto: producto },
        success: function (json) {
            if (json.status == 'error') {
                
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    if (value) {
                        $(".alertaCodigo").removeClass('hidden').html('<small>'+json.message+'</small>');
                        $("#nombreP").focus();
                    }
                })
            }
            if (json.status == 'vacio') {
                $(".validarC").val(json.codigo);
                $(".alertaCodigo").addClass('hidden').html('');
            }
            if (json.status == 'success'){
                $(".alertaCodigo").addClass('hidden').html('');
            }
        },
        error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    })
});
/* /.Validar que el codigo del producto no exista ya modal agregar */

/* Validar que el nombre del producto no exista ya modal agregar */
$(document).on('change', '.validarP', function () {
    codigo = $(".validarC").val();
    producto = $(this).val();
    $.ajax({
    	url: "api/validarProducto",
    	method: 'POST',
    	dataType: 'json',
    	data: { codigo: codigo, producto: producto },
    	success: function (json) {
	        if (json.status == 'error') {
	            swal({
	                title: json.title,
	                text: json.message,
	                icon: "error",
	                closeOnClickOutside: false,
	                closeOnEsc: true,
	                buttons: {
	                    cancel: {
	                        text: "Cerrar",
	                        value: true,
	                        visible: true,
	                        className: "btn btn-sm btn-flat btn-default text-uppercase font-weight-bold",
	                        closeModal: true,
	                    }
	                }
	            })
                .then((value) => {
                    if (value) {
                        $(".validarP").val("");
                        $(".validarP").focus();
                    }
                });
	        } else if (json.status == "success") {
                $(".validarC").val(json.codigo);
	        } else {
                $(".validarC").val(json.codigo);
	        }
    	},
    	error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
    	}
    })
});
/* /.Validar que el nombre del producto no exista ya modal agregar */


/* JS REGISTRAR COMPRA */